<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ExtendedMethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

/**
 * @implements Rule<Class_>
 */
final class PostMountMethodSignatureRule implements Rule
{
    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
            return [];
        }

        if ($node->namespacedName === null) {
            return [];
        }

        $errors = [];
        $reflClass = $this->reflectionProvider->getClass($node->namespacedName->toString());

        foreach ($node->getMethods() as $method) {
            if (! AttributeFinder::findAttribute($method, PostMount::class)) {
                continue;
            }

            $errors[] = $this->validatePostMountMethod(
                $method,
                $reflClass->getMethod($method->name->name, $scope),
            );
        }

        return array_merge(...$errors);
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    private function validatePostMountMethod(Node\Stmt\ClassMethod $method, ExtendedMethodReflection $reflMethod): array
    {
        $errors = [];

        $methodName = $reflMethod->getName();
        $methodParams = $reflMethod->getOnlyVariant()->getParameters();
        $methodReturnType = $reflMethod->getOnlyVariant()->getReturnType();

        // Check if method is public
        if (! $reflMethod->isPublic()) {
            $errors[] = RuleErrorBuilder::message(sprintf('Method "%s" with #[PostMount] attribute must be public.', $methodName))
                ->identifier('symfonyUX.twigComponent.postMountPublic')
                ->line($method->getLine())
                ->tip('Change the method visibility to public.')
                ->build();
        }

        // Check parameter count and type (0 or 1 parameter allowed)
        if (count($methodParams) > 1) {
            $errors[] = RuleErrorBuilder::message(sprintf('Method "%s" with #[PostMount] attribute must have at most one parameter of type "array".', $methodName))
                ->identifier('symfonyUX.twigComponent.postMountParameterCount')
                ->line($method->getLine())
                ->tip('The method should have zero or one parameter: "array $data" (optional).')
                ->build();
        } elseif (count($methodParams) === 1) {
            // If there is a parameter, it must be of type array
            if (! $methodParams[0]->getType()->isArray()->yes()) {
                $errors[] = RuleErrorBuilder::message(sprintf('Method "%s" with #[PostMount] attribute must have a parameter of type "array".', $methodName))
                    ->identifier('symfonyUX.twigComponent.postMountParameterType')
                    ->line($method->getLine())
                    ->tip('Change the parameter type to "array".')
                    ->build();
            }
        }

        // Check return type (must be array, void, or array|void)
        $isValidReturnType = $methodReturnType->isVoid()->yes()
            || $methodReturnType->isArray()->yes()
            || ($methodReturnType->isArray()->maybe() && $methodReturnType->isVoid()->maybe());

        if (! $isValidReturnType) {
            $errors[] = RuleErrorBuilder::message(sprintf('Method "%s" with #[PostMount] attribute must have a return type of "array", "void", or "array|void".', $methodName))
                ->identifier('symfonyUX.twigComponent.postMountReturnType')
                ->line($method->getLine())
                ->tip('Change the return type to ": array", ": void", or ": array|void".')
                ->build();
        }

        return $errors;
    }
}

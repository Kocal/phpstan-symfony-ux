<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

/**
 * @implements Rule<Class_>
 */
final class PreMountMethodSignatureRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
            return [];
        }

        $errors = [];

        foreach ($node->getMethods() as $method) {
            // Check if the method has the PreMount attribute
            if (! AttributeFinder::findAttribute($method, PreMount::class)) {
                continue;
            }

            $errors = array_merge($errors, $this->validatePreMountMethod($method));
        }

        return $errors;
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    private function validatePreMountMethod(Node\Stmt\ClassMethod $node): array
    {
        $errors = [];

        // Check if the method is public
        if (! $node->isPublic()) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Method "%s" with #[PreMount] attribute must be public.', $node->name->toString())
            )
                ->identifier('symfonyUX.twigComponent.preMountMethodMustBePublic')
                ->line($node->getLine())
                ->tip('Change the method visibility to public.')
                ->build();
        }

        // Check the return type
        $returnType = $node->getReturnType();
        if ($returnType === null) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Method "%s" with #[PreMount] attribute must have a return type of "array".', $node->name->toString())
            )
                ->identifier('symfonyUX.twigComponent.preMountMethodMissingReturnType')
                ->line($node->getLine())
                ->tip('Add ": array" return type to the method.')
                ->build();
        } else {
            $isValidReturnType = $returnType instanceof Node\Identifier && $returnType->toString() === 'array';

            if (! $isValidReturnType) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf('Method "%s" with #[PreMount] attribute must have a return type of "array".', $node->name->toString())
                )
                    ->identifier('symfonyUX.twigComponent.preMountMethodInvalidReturnType')
                    ->line($returnType->getLine())
                    ->tip('Change the return type to ": array".')
                    ->build();
            }
        }

        // Check that there is exactly one parameter of type array
        if (count($node->params) !== 1) {
            $errors[] = RuleErrorBuilder::message(
                sprintf('Method "%s" with #[PreMount] attribute must have exactly one parameter of type "array".', $node->name->toString())
            )
                ->identifier('symfonyUX.twigComponent.preMountMethodInvalidParameterCount')
                ->line($node->getLine())
                ->tip('The method should have exactly one parameter: "array $data".')
                ->build();
        } else {
            $param = $node->params[0];
            $paramType = $param->type;

            if (! $paramType instanceof Node\Identifier || $paramType->toString() !== 'array') {
                $errors[] = RuleErrorBuilder::message(
                    sprintf('Method "%s" with #[PreMount] attribute must have a parameter of type "array".', $node->name->toString())
                )
                    ->identifier('symfonyUX.twigComponent.preMountMethodInvalidParameterType')
                    ->line($param->getLine())
                    ->tip('Change the parameter type to "array".')
                    ->build();
            }
        }

        return $errors;
    }
}

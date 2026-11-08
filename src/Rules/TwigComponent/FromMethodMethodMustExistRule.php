<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\StringType;
use PHPStan\Type\VerbosityLevel;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\FromMethod;

/**
 * @implements Rule<Class_>
 */
final class FromMethodMethodMustExistRule implements Rule
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
        $componentAttribute = AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class]);
        if (! $componentAttribute) {
            return [];
        }

        if ($node->namespacedName === null) {
            return [];
        }

        $methodName = $this->extractFromMethodName($componentAttribute);
        if ($methodName === null) {
            return [];
        }

        $errors = [];
        $className = $node->namespacedName->toString();
        $reflClass = $this->reflectionProvider->getClass($className);

        if (! $reflClass->hasMethod($methodName)) {
            return [
                RuleErrorBuilder::message(
                    sprintf(
                        'Component "%s" references non-existent method "%s()" in FromMethod.',
                        $className,
                        $methodName
                    )
                )
                    ->identifier('symfonyUX.twigComponent.fromMethodMethodMustExist')
                    ->line($componentAttribute->getLine())
                    ->tip(sprintf('Create the public method "%s()" in the component class.', $methodName))
                    ->build(),
            ];
        }

        $methodRefl = $reflClass->getMethod($methodName, $scope);
        $methodLine = $node->getMethod($methodName)?->getLine() ?? $componentAttribute->getLine();

        if (! $methodRefl->isPublic()) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Method "%s()" referenced in FromMethod must be public.',
                    $methodName
                )
            )
                ->identifier('symfonyUX.twigComponent.fromMethodMethodMustBePublic')
                ->line($methodLine)
                ->tip(sprintf('Make the method "%s()" public.', $methodName))
                ->build();
        }

        $returnType = $methodRefl->getOnlyVariant()->getReturnType();

        if (! (new StringType())->isSuperTypeOf($returnType)->yes()) {
            $errors[] = RuleErrorBuilder::message(
                sprintf(
                    'Method "%s()" referenced in FromMethod must return string, got "%s".',
                    $methodName,
                    $returnType->describe(VerbosityLevel::typeOnly())
                )
            )
                ->identifier('symfonyUX.twigComponent.fromMethodMethodMustReturnString')
                ->line($methodLine)
                ->tip(sprintf('Change the return type of "%s()" to string.', $methodName))
                ->build();
        }

        return $errors;
    }

    private function extractFromMethodName(Node\Attribute $attribute): ?string
    {
        // $template is the 2nd constructor parameter of both AsTwigComponent and AsLiveComponent
        $templateArg = AttributeFinder::findArgument($attribute, 'template', 1);

        return $templateArg ? $this->extractMethodNameFromNewExpression($templateArg->value) : null;
    }

    private function extractMethodNameFromNewExpression(Node\Expr $expr): ?string
    {
        if (! $expr instanceof New_ || ! $expr->class instanceof FullyQualified || $expr->class->toString() !== FromMethod::class) {
            return null;
        }

        $firstArg = $expr->args[0] ?? null;

        return $firstArg instanceof Node\Arg && $firstArg->value instanceof String_ ? $firstArg->value->value : null;
    }
}

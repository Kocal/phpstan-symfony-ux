<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\LiveComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\VerbosityLevel;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

/**
 * @implements Rule<Class_>
 */
final class LivePropModifierMethodRule implements Rule
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
        if (! AttributeFinder::findAttribute($node, AsLiveComponent::class)) {
            return [];
        }

        if ($node->namespacedName === null) {
            return [];
        }

        $errors = [];
        $reflClass = $this->reflectionProvider->getClass($node->namespacedName->toString());

        foreach ($node->getProperties() as $property) {
            $livePropAttribute = AttributeFinder::findAttribute($property, LiveProp::class);
            if (! $livePropAttribute) {
                continue;
            }

            // Extract modifier method name from the attribute
            $modifier = $this->getArgumentValue($livePropAttribute, 'modifier');

            // Skip if modifier argument is not defined
            if ($modifier === null) {
                continue;
            }

            $propertyName = $property->props[0]->name->toString();

            // Check if the modifier method exists
            if (! $reflClass->hasMethod($modifier)) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Property "%s" references non-existent modifier method "%s()".',
                        $propertyName,
                        $modifier
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropModifierMethodMustExist')
                    ->line($property->getLine())
                    ->tip(sprintf('Create the public method "%s()" in the component class.', $modifier))
                    ->build();

                continue;
            }

            // Get method reflection
            $modifierMethodRefl = $reflClass->getMethod($modifier, $scope);

            // Get AST node for line number
            $modifierMethodNode = $node->getMethod($modifier);

            // Check that method is public
            if (! $modifierMethodRefl->isPublic()) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Modifier method "%s()" referenced in property "%s" must be public.',
                        $modifier,
                        $propertyName
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropModifierMethodMustBePublic')
                    ->line($modifierMethodNode?->getLine() ?? $property->getLine())
                    ->tip(sprintf('Make the method "%s()" public.', $modifier))
                    ->build();
            }

            // Get method signature
            $modifierVariant = $modifierMethodRefl->getOnlyVariant();
            $modifierParams = $modifierVariant->getParameters();
            $modifierReturnType = $modifierVariant->getReturnType();

            // Check that modifier method has 1 or 2 parameters
            $paramCount = count($modifierParams);
            if ($paramCount < 1 || $paramCount > 2) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Modifier method "%s()" must have 1 or 2 parameters (LiveProp and optionally string).',
                        $modifier
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropModifierMethodParameterCount')
                    ->line($modifierMethodNode?->getLine() ?? $property->getLine())
                    ->tip('The modifier method should have a LiveProp parameter and optionally a string parameter.')
                    ->build();
            } else {
                // Check first parameter is LiveProp
                $firstParamType = $modifierParams[0]->getType();
                $expectedLivePropType = new ObjectType(LiveProp::class);

                if (! $expectedLivePropType->isSuperTypeOf($firstParamType)->yes()) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf(
                            'Modifier method "%s()" first parameter must be of type "%s", got "%s".',
                            $modifier,
                            LiveProp::class,
                            $firstParamType->describe(VerbosityLevel::typeOnly())
                        )
                    )
                        ->identifier('symfonyUX.liveComponent.livePropModifierMethodFirstParameterType')
                        ->line($modifierMethodNode?->getLine() ?? $property->getLine())
                        ->tip(sprintf('Change the first parameter type to "%s".', LiveProp::class))
                        ->build();
                }

                // Check second parameter is string if it exists
                if ($paramCount === 2) {
                    $secondParamType = $modifierParams[1]->getType();
                    $expectedStringType = new StringType();

                    if (! $expectedStringType->isSuperTypeOf($secondParamType)->yes()) {
                        $errors[] = RuleErrorBuilder::message(
                            sprintf(
                                'Modifier method "%s()" second parameter must be of type "string", got "%s".',
                                $modifier,
                                $secondParamType->describe(VerbosityLevel::typeOnly())
                            )
                        )
                            ->identifier('symfonyUX.liveComponent.livePropModifierMethodSecondParameterType')
                            ->line($modifierMethodNode?->getLine() ?? $property->getLine())
                            ->tip('Change the second parameter type to "string".')
                            ->build();
                    }
                }
            }

            // Check that modifier method returns LiveProp
            $expectedLivePropType = new ObjectType(LiveProp::class);
            if (! $expectedLivePropType->isSuperTypeOf($modifierReturnType)->yes()) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Modifier method "%s()" must return "%s", got "%s".',
                        $modifier,
                        LiveProp::class,
                        $modifierReturnType->describe(VerbosityLevel::typeOnly())
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropModifierMethodReturnType')
                    ->line($modifierMethodNode?->getLine() ?? $property->getLine())
                    ->tip(sprintf('Change the return type to ": %s".', LiveProp::class))
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * Extracts the value of a named argument from a LiveProp attribute.
     */
    private function getArgumentValue(Node\Attribute $attribute, string $argumentName): ?string
    {
        foreach ($attribute->args as $arg) {
            if ($arg->name && $arg->name->toString() === $argumentName) {
                if ($arg->value instanceof Node\Scalar\String_) {
                    return $arg->value->value;
                }
            }
        }

        return null;
    }
}

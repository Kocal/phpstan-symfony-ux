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
use PHPStan\Type\VerbosityLevel;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

/**
 * @implements Rule<Class_>
 */
final class LivePropHydrationMethodsRule implements Rule
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

            // Extract hydration method names from the attribute
            $hydrateWith = $this->getArgumentValue($livePropAttribute, 'hydrateWith');
            $dehydrateWith = $this->getArgumentValue($livePropAttribute, 'dehydrateWith');

            // Skip if both arguments are not defined
            if ($hydrateWith === null && $dehydrateWith === null) {
                continue;
            }

            // TODO: Is there a best way to do this?
            $propertyName = $property->props[0]->name->toString();

            // Ensure that "dehydrateWith" is specified when "hydrateWith" is specified
            if ($hydrateWith !== null && $dehydrateWith === null) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Property "%s" has a #[LiveProp] attribute with "hydrateWith" but is missing "dehydrateWith".',
                        $propertyName
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropHydrationMethodsMustBothExist')
                    ->line($property->getLine())
                    ->tip('Both "hydrateWith" and "dehydrateWith" must be specified together in the #[LiveProp] attribute.')
                    ->build();

                continue;
            }

            // Ensure that "hydrateWith" is specified when "dehydrateWith" is specified
            if ($dehydrateWith !== null && $hydrateWith === null) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Property "%s" has a #[LiveProp] attribute with "dehydrateWith" but is missing "hydrateWith".',
                        $propertyName
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropHydrationMethodsMustBothExist')
                    ->line($property->getLine())
                    ->tip('Both "hydrateWith" and "dehydrateWith" must be specified together in the #[LiveProp] attribute.')
                    ->build();

                continue;
            }

            // At this point, both $hydrateWith and $dehydrateWith are guaranteed to be non-null (validated above)
            assert($hydrateWith !== null && $dehydrateWith !== null);

            // Get method reflections using PHPStan's reflection system
            $hydrateMethodExists = $reflClass->hasMethod($hydrateWith);
            $dehydrateMethodExists = $reflClass->hasMethod($dehydrateWith);

            // Validate that hydrate method exists
            if (! $hydrateMethodExists) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Property "%s" references non-existent hydrate method "%s()".',
                        $propertyName,
                        $hydrateWith
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropHydrationMethodMustExist')
                    ->line($property->getLine())
                    ->tip(sprintf('Create the public method "%s()" in the component class.', $hydrateWith))
                    ->build();
            }

            // Validate that dehydrate method exists
            if (! $dehydrateMethodExists) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Property "%s" references non-existent dehydrate method "%s()".',
                        $propertyName,
                        $dehydrateWith
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropDehydrationMethodMustExist')
                    ->line($property->getLine())
                    ->tip(sprintf('Create the public method "%s()" in the component class.', $dehydrateWith))
                    ->build();
            }

            // Skip further validation if methods don't exist
            if (! $hydrateMethodExists || ! $dehydrateMethodExists) {
                continue;
            }

            // Get reflection of both methods
            $hydrateMethodRefl = $reflClass->getMethod($hydrateWith, $scope);
            $dehydrateMethodRefl = $reflClass->getMethod($dehydrateWith, $scope);

            // Get AST nodes for line numbers
            $hydrateMethodNode = $this->findMethod($node, $hydrateWith);
            $dehydrateMethodNode = $this->findMethod($node, $dehydrateWith);

            // Check that methods are public
            if (! $hydrateMethodRefl->isPublic()) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Hydrate method "%s()" referenced in property "%s" must be public.',
                        $hydrateWith,
                        $propertyName
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropHydrationMethodMustBePublic')
                    ->line($hydrateMethodNode?->getLine() ?? $property->getLine())
                    ->tip(sprintf('Make the method "%s()" public.', $hydrateWith))
                    ->build();
            }

            if (! $dehydrateMethodRefl->isPublic()) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Dehydrate method "%s()" referenced in property "%s" must be public.',
                        $dehydrateWith,
                        $propertyName
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropDehydrationMethodMustBePublic')
                    ->line($dehydrateMethodNode?->getLine() ?? $property->getLine())
                    ->tip(sprintf('Make the method "%s()" public.', $dehydrateWith))
                    ->build();
            }

            // Check that methods have compatible types using PHPStan's type system
            // Get the property type from reflection
            if (! $reflClass->hasProperty($propertyName)) {
                continue;
            }

            $propertyRefl = $reflClass->getProperty($propertyName, $scope);
            $propertyType = $propertyRefl->getReadableType();

            // Get method signatures
            $hydrateVariant = $hydrateMethodRefl->getOnlyVariant();
            $dehydrateVariant = $dehydrateMethodRefl->getOnlyVariant();

            $hydrateParams = $hydrateVariant->getParameters();
            $hydrateReturnType = $hydrateVariant->getReturnType();

            $dehydrateParams = $dehydrateVariant->getParameters();
            $dehydrateReturnType = $dehydrateVariant->getReturnType();

            // Check that hydrate method has one parameter
            if (count($hydrateParams) !== 1) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Hydrate method "%s()" must have one parameter.',
                        $hydrateWith
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropHydrateMethodMustHaveParameter')
                    ->line($hydrateMethodNode?->getLine() ?? $property->getLine())
                    ->tip('Add a parameter to the hydrate method.')
                    ->build();
            }

            // Check that hydrate method return type matches property type
            if (! $hydrateReturnType->equals($propertyType)) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Hydrate method "%s()" return type must match property "%s" type. Expected "%s", got "%s".',
                        $hydrateWith,
                        $propertyName,
                        $propertyType->describe(VerbosityLevel::typeOnly()),
                        $hydrateReturnType->describe(VerbosityLevel::typeOnly())
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropHydrateMethodReturnTypeMismatch')
                    ->line($hydrateMethodNode?->getLine() ?? $property->getLine())
                    ->tip(sprintf('Change the return type to ": %s".', $propertyType->describe(VerbosityLevel::typeOnly())))
                    ->build();
            }

            // Check that dehydrate method has one parameter
            if (count($dehydrateParams) !== 1) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Dehydrate method "%s()" must have one parameter that matches property "%s" type.',
                        $dehydrateWith,
                        $propertyName
                    )
                )
                    ->identifier('symfonyUX.liveComponent.livePropDehydrateMethodMustHaveParameter')
                    ->line($dehydrateMethodNode?->getLine() ?? $property->getLine())
                    ->tip('Add a parameter to the dehydrate method that matches the property type.')
                    ->build();
            } else {
                // Check that dehydrate method first parameter type matches property type
                $dehydrateParamType = $dehydrateParams[0]->getType();
                if (! $dehydrateParamType->equals($propertyType)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf(
                            'Dehydrate method "%s()" first parameter type must match property "%s" type. Expected "%s", got "%s".',
                            $dehydrateWith,
                            $propertyName,
                            $propertyType->describe(VerbosityLevel::typeOnly()),
                            $dehydrateParamType->describe(VerbosityLevel::typeOnly())
                        )
                    )
                        ->identifier('symfonyUX.liveComponent.livePropDehydrateMethodParameterTypeMismatch')
                        ->line($dehydrateMethodNode?->getLine() ?? $property->getLine())
                        ->tip(sprintf('Change the parameter type to "%s".', $propertyType->describe(VerbosityLevel::typeOnly())))
                        ->build();
                }
            }

            // Check that hydration and dehydration methods are cross-compatible
            // The dehydrate method's return type should match the hydrate method's parameter type
            if (count($hydrateParams) > 0) {
                $hydrateParamType = $hydrateParams[0]->getType();
                if (! $dehydrateReturnType->equals($hydrateParamType)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf(
                            'Dehydrate method "%s()" return type must match hydrate method "%s()" first parameter type. Expected "%s", got "%s".',
                            $dehydrateWith,
                            $hydrateWith,
                            $hydrateParamType->describe(VerbosityLevel::typeOnly()),
                            $dehydrateReturnType->describe(VerbosityLevel::typeOnly())
                        )
                    )
                        ->identifier('symfonyUX.liveComponent.livePropHydrationMethodsTypeMismatch')
                        ->line($dehydrateMethodNode?->getLine() ?? $property->getLine())
                        ->tip(sprintf(
                            'The dehydrate method should return the same type that the hydrate method accepts as its first parameter: "%s".',
                            $hydrateParamType->describe(VerbosityLevel::typeOnly())
                        ))
                        ->build();
                }
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

    /**
     * Finds a method AST node by name in the given class (used for line numbers).
     */
    private function findMethod(Class_ $classNode, string $methodName): ?Node\Stmt\ClassMethod
    {
        foreach ($classNode->getMethods() as $method) {
            if ($method->name->toString() === $methodName) {
                return $method;
            }
        }

        return null;
    }
}

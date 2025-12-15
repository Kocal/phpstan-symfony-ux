<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * @implements Rule<Class_>
 */
final class MethodsShouldBePublicOrPrivateRule implements Rule
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

        $className = $node->namespacedName->toString();
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $errors = [];
        $classReflection = $this->reflectionProvider->getClass($className);
        $abstractTraitMethods = $this->getAbstractTraitMethods($classReflection);

        foreach ($node->getMethods() as $method) {
            if (! $method->isProtected()) {
                continue;
            }

            $methodName = $method->name->toString();

            // Skip if the method implements an abstract method from a trait
            if (isset($abstractTraitMethods[$methodName])) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                sprintf('Method "%s()" in a Twig component should not be protected.', $methodName)
            )
                ->identifier('symfonyUX.twigComponent.methodsShouldBePublicOrPrivate')
                ->line($method->getLine())
                ->tip('Twig component methods should be either public or private, not protected.')
                ->build();
        }

        // Check for protected concrete methods from traits that are not overridden in the class
        $classDefinedMethods = [];
        foreach ($node->getMethods() as $method) {
            $classDefinedMethods[$method->name->toString()] = true;
        }

        $reportedTraitMethods = [];
        foreach ($classReflection->getTraits() as $traitReflection) {
            // Use native reflection to get trait methods
            foreach ($traitReflection->getNativeReflection()->getMethods() as $traitMethod) {
                if (! $traitMethod->isProtected()) {
                    continue;
                }

                $methodName = $traitMethod->getName();

                // Skip if method is abstract (already handled above)
                if (isset($abstractTraitMethods[$methodName])) {
                    continue;
                }

                // Skip if method is overridden in the class (already checked above)
                if (isset($classDefinedMethods[$methodName])) {
                    continue;
                }

                // Skip if we already reported an error for this method name
                if (isset($reportedTraitMethods[$methodName])) {
                    continue;
                }

                $reportedTraitMethods[$methodName] = true;

                // Find the line number from the trait use statement
                $lineNumber = $node->getLine();
                foreach ($node->getTraitUses() as $traitUse) {
                    $lineNumber = $traitUse->getLine();
                    break;
                }

                $errors[] = RuleErrorBuilder::message(
                    sprintf('Method "%s()" in a Twig component should not be protected.', $methodName)
                )
                    ->identifier('symfonyUX.twigComponent.methodsShouldBePublicOrPrivate')
                    ->line($lineNumber)
                    ->tip('Twig component methods should be either public or private, not protected.')
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * Get the list of abstract method names defined in traits used by the class.
     *
     * @return array<string, true>
     */
    private function getAbstractTraitMethods(ClassReflection $classReflection): array
    {
        $abstractTraitMethods = [];

        foreach ($classReflection->getTraits() as $traitReflection) {
            foreach ($traitReflection->getNativeReflection()->getMethods() as $method) {
                if ($method->isAbstract()) {
                    $abstractTraitMethods[$method->getName()] = true;
                }
            }
        }

        return $abstractTraitMethods;
    }
}

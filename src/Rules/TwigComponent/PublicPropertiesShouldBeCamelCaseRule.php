<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * @implements Rule<Class_>
 */
final class PublicPropertiesShouldBeCamelCaseRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAttribute($node, AsTwigComponent::class)) {
            return [];
        }

        $errors = [];

        foreach ($node->getProperties() as $property) {
            if (! $property->isPublic()) {
                continue;
            }

            foreach ($property->props as $prop) {
                $propertyName = $prop->name->toString();

                if (! $this->isCamelCase($propertyName)) {
                    $errors[] = RuleErrorBuilder::message(
                        sprintf('Public property "%s" in a Twig component should be in camelCase.', $propertyName)
                    )
                        ->identifier('symfonyUX.twigComponent.publicPropertiesShouldBeCamelCase')
                        ->line($property->getLine())
                        ->tip(sprintf('Consider renaming "%s" to "%s".', $propertyName, $this->toCamelCase($propertyName)))
                        ->build();
                }
            }
        }

        return $errors;
    }

    private function isCamelCase(string $name): bool
    {
        // Property should start with a lowercase letter and contain no underscores
        return preg_match('/^[a-z][a-zA-Z0-9]*$/', $name) === 1;
    }

    private function toCamelCase(string $name): string
    {
        // Convert snake_case or PascalCase to camelCase
        $name = str_replace('_', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return lcfirst($name);
    }
}

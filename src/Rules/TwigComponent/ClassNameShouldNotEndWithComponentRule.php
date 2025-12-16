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

/**
 * @implements Rule<Class_>
 */
final class ClassNameShouldNotEndWithComponentRule implements Rule
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

        $className = $node->name;
        if ($className === null) {
            return [];
        }

        $classNameString = $className->toString();
        if (str_ends_with($classNameString, 'Component')) {
            return [
                RuleErrorBuilder::message(sprintf('Twig component class "%s" must not end with "Component".', $classNameString))
                    ->identifier('symfonyUX.twigComponent.classNameShouldNotEndWithComponent')
                    ->line($className->getLine())
                    ->tip('Remove the "Component" suffix from the class name.')
                    ->build(),
            ];
        }

        return [];
    }
}

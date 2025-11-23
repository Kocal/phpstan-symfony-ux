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
final class ClassMustBeFinalRule implements Rule
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

        if ($node->isAbstract()) {
            return [
                RuleErrorBuilder::message('Twig component class must be final, not abstract.')
                    ->identifier('symfonyUX.twigComponent.classMustBeFinal')
                    ->tip('Make the class final and use traits for composition instead of inheritance.')
                    ->build(),
            ];
        }

        if (! $node->isFinal()) {
            return [
                RuleErrorBuilder::message('Twig component class must be final.')
                    ->identifier('symfonyUX.twigComponent.classMustBeFinal')
                    ->tip('Add the "final" keyword to the class declaration to prevent inheritance.')
                    ->build(),
            ];
        }

        return [];
    }
}

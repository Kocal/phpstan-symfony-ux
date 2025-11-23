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
final class ForbiddenInheritanceRule implements Rule
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

        if ($node->extends !== null) {
            return [
                RuleErrorBuilder::message('Using class inheritance in a Twig component is forbidden, use traits for composition instead.')
                    ->identifier('symfonyUX.twigComponent.forbiddenClassInheritance')
                    ->line($node->extends->getLine())
                    ->tip('Consider using traits to share common functionality between Twig components.')
                    ->build(),
            ];
        }

        return [];
    }
}

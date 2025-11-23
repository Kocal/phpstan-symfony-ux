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
final class ForbiddenClassPropertyRule implements Rule
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

        if ($propertyClass = $node->getProperty('class')) {
            return [
                RuleErrorBuilder::message('Using a "class" property in a Twig component is forbidden, it is considered as an anti-pattern.')
                    ->identifier('symfonyUX.twigComponent.forbiddenClassProperty')
                    ->line($propertyClass->getLine())
                    ->tip('Consider using {{ attributes }} to automatically render unknown properties as HTML attributes, such as "class". Learn more at https://symfony.com/bundles/ux-twig-component/current/index.html#component-attributes.')
                    ->build(),

            ];
        }

        return [];
    }
}

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
final class MethodsShouldBePublicOrPrivateRule implements Rule
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
            if ($method->isProtected()) {
                $methodName = $method->name->toString();

                $errors[] = RuleErrorBuilder::message(
                    sprintf('Method "%s()" in a Twig component should not be protected.', $methodName)
                )
                    ->identifier('symfonyUX.twigComponent.methodsShouldBePublicOrPrivate')
                    ->line($method->getLine())
                    ->tip('Twig component methods should be either public or private, not protected.')
                    ->build();
            }
        }

        return $errors;
    }
}

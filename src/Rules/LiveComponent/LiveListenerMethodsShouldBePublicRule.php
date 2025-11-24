<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\LiveComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

/**
 * @implements Rule<Class_>
 */
final class LiveListenerMethodsShouldBePublicRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAnyAttribute($node, [AsLiveComponent::class])) {
            return [];
        }

        $errors = [];

        foreach ($node->getMethods() as $method) {
            if (! AttributeFinder::findAnyAttribute($method, [LiveListener::class])) {
                continue;
            }

            if (! $method->isPublic()) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'LiveListener method "%s()" should be public.',
                    $method->name->toString()
                ))
                    ->identifier('symfonyUX.liveComponent.liveListenerMethodShouldBePublic')
                    ->line($method->getLine())
                    ->tip('Change the method visibility to public.')
                    ->build();
            }
        }

        return $errors;
    }
}

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
use Symfony\UX\LiveComponent\Attribute\LiveAction;

/**
 * @implements Rule<Class_>
 */
class LiveActionMethodsVisibilityRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAttribute($node, AsLiveComponent::class)) {
            return [];
        }

        $errors = [];

        foreach ($node->getMethods() as $method) {
            if (! AttributeFinder::findAttribute($method, LiveAction::class)) {
                continue;
            }

            if (! $method->isPublic()) {
                $methodName = $method->name->toString();

                $errors[] = RuleErrorBuilder::message(
                    sprintf('LiveAction method "%s()" must be public.', $methodName)
                )
                    ->identifier('symfonyUX.liveComponent.liveActionMethodsVisibility')
                    ->line($method->getLine())
                    ->tip('Methods annotated with #[LiveAction] must be public to be accessible as component actions.')
                    ->build();
            }
        }

        return $errors;
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\LiveComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\TypeCombinator;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\LiveResponse;

/**
 * @implements Rule<Class_>
 */
final class LiveResponseMustBeReturnedByLiveActionRule implements Rule
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
        if (! $attribute = AttributeFinder::findAttribute($node, AsLiveComponent::class)) {
            return [];
        }

        if ($node->namespacedName === null) {
            return [];
        }

        $errors = [];
        $reflClass = $this->reflectionProvider->getClass($node->namespacedName->toString());
        $defaultAction = $this->getDefaultAction($attribute);

        foreach ($node->getMethods() as $method) {
            $methodName = $method->name->toString();
            $returnType = TypeCombinator::removeNull($reflClass->getNativeMethod($methodName)->getOnlyVariant()->getReturnType());

            if (! in_array(LiveResponse::class, $returnType->getObjectClassNames(), true)) {
                continue;
            }

            if ($defaultAction !== null && strcasecmp($methodName, $defaultAction) === 0) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf('Default action "%s()" must not return a LiveResponse.', $methodName)
                )
                    ->identifier('symfonyUX.liveComponent.liveResponseInDefaultAction')
                    ->line($method->getLine())
                    ->tip('The default action runs on every re-render, return the LiveResponse from a #[LiveAction] or #[LiveListener] method instead.')
                    ->build();

                continue;
            }

            if ($method->isPublic() && ! AttributeFinder::findAnyAttribute($method, [LiveAction::class, LiveListener::class])) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf('Method "%s()" returning a LiveResponse must be a LiveAction or a LiveListener.', $methodName)
                )
                    ->identifier('symfonyUX.liveComponent.liveResponseMustBeReturnedByLiveAction')
                    ->line($method->getLine())
                    ->tip(sprintf('Add the #[LiveAction] attribute to "%s()", or make it private if it only builds the LiveResponse for another action.', $methodName))
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * Returns null when the "defaultAction" argument cannot be resolved statically.
     */
    private function getDefaultAction(Node\Attribute $attribute): ?string
    {
        $defaultActionArg = AttributeFinder::findArgument($attribute, 'defaultAction', 2);

        if (! $defaultActionArg) {
            return '__invoke';
        }

        return $defaultActionArg->value instanceof String_ ? trim($defaultActionArg->value->value, '()') : null;
    }
}

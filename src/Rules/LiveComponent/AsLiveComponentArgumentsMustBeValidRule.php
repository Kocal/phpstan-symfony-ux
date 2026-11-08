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

/**
 * @implements Rule<Class_>
 */
final class AsLiveComponentArgumentsMustBeValidRule implements Rule
{
    private const METHODS = ['get', 'post'];

    private const FETCH_CREDENTIALS = ['same-origin', 'include', 'omit'];

    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $attribute = AttributeFinder::findAttribute($node, AsLiveComponent::class)) {
            return [];
        }

        $errors = [];
        $positionOffset = $this->isDeprecatedCsrfPassedPositionally($attribute, $scope) ? 1 : 0;

        if ($methodArg = $this->findArgument($attribute, 'method', 6 + $positionOffset)) {
            foreach ($scope->getType($methodArg->value)->getConstantStrings() as $method) {
                if (in_array(strtolower($method->getValue()), self::METHODS, true)) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message(
                    sprintf('The "method" parameter of the #[AsLiveComponent] attribute must be "get" or "post", got "%s".', $method->getValue())
                )
                    ->identifier('symfonyUX.liveComponent.asLiveComponentMethodMustBeValid')
                    ->line($methodArg->getLine())
                    ->tip('Set the "method" parameter to "get" or "post".')
                    ->build();
            }
        }

        if ($fetchCredentialsArg = $this->findArgument($attribute, 'fetchCredentials', 8 + $positionOffset)) {
            foreach ($scope->getType($fetchCredentialsArg->value)->getConstantStrings() as $fetchCredentials) {
                if (in_array($fetchCredentials->getValue(), self::FETCH_CREDENTIALS, true)) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message(
                    sprintf('The "fetchCredentials" parameter of the #[AsLiveComponent] attribute must be "same-origin", "include" or "omit", got "%s".', $fetchCredentials->getValue())
                )
                    ->identifier('symfonyUX.liveComponent.asLiveComponentFetchCredentialsMustBeValid')
                    ->line($fetchCredentialsArg->getLine())
                    ->tip('Set the "fetchCredentials" parameter to "same-origin", "include" or "omit", or remove it to use the "live_component.fetch_credentials" configuration.')
                    ->build();
            }
        }

        return $errors;
    }

    private function findArgument(Node\Attribute $attribute, string $name, int $position): ?Node\Arg
    {
        foreach ($attribute->args as $index => $arg) {
            $isWantedArg = $arg->name === null
                ? $index === $position
                : $arg->name->toString() === $name;

            if ($isWantedArg) {
                return $arg;
            }
        }

        return null;
    }

    // symfony/ux-live-component 2.x still accepts the deprecated "csrf" boolean as 6th argument, shifting the next ones
    private function isDeprecatedCsrfPassedPositionally(Node\Attribute $attribute, Scope $scope): bool
    {
        $arg = $attribute->args[5] ?? null;

        return $arg !== null && $arg->name === null && $scope->getType($arg->value)->isBoolean()->yes();
    }
}

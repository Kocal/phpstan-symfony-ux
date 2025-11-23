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
final class ExposePublicPropsShouldBeFalseRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $attribute = AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
            return [];
        }

        $exposePublicPropsValue = $this->getExposePublicPropsValue($attribute);

        if ($exposePublicPropsValue !== false) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'The #[%s] attribute must have its "exposePublicProps" parameter set to false.',
                    $attribute->name->getLast(),
                ))
                    ->identifier('symfonyUX.twigComponent.exposePublicPropsShouldBeFalse')
                    ->line($attribute->getLine())
                    ->tip(sprintf(
                        'Set "exposePublicProps" to false in the #[%s] attribute.',
                        $attribute->name->getLast()
                    ))
                    ->build(),
            ];
        }

        return [];
    }

    private function getExposePublicPropsValue(Node\Attribute $attribute): ?bool
    {
        foreach ($attribute->args as $arg) {
            if ($arg->name && $arg->name->toString() === 'exposePublicProps') {
                if ($arg->value instanceof Node\Expr\ConstFetch) {
                    $constantName = $arg->value->name->toString();

                    return match (strtolower($constantName)) {
                        'true' => true,
                        'false' => false,
                        default => null,
                    };
                }
            }
        }

        return null;
    }
}

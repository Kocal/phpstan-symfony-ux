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
final class ExposePublicPropsShouldBeFalseRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $asTwigComponent = AttributeFinder::findAttribute($node, AsTwigComponent::class)) {
            return [];
        }

        $exposePublicPropsValue = $this->getExposePublicPropsValue($asTwigComponent);

        if ($exposePublicPropsValue !== false) {
            return [
                RuleErrorBuilder::message('The #[AsTwigComponent] attribute must have its "exposePublicProps" parameter set to false.')
                    ->identifier('symfonyUX.twigComponent.exposePublicPropsShouldBeFalse')
                    ->line($asTwigComponent->getLine())
                    ->tip('Set "exposePublicProps" to false in the #[AsTwigComponent] attribute.')
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

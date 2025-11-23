<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenInheritanceRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenInheritanceRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ForbiddenInheritanceRule>
 */
final class ForbiddenInheritanceRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithInheritance.php'],
            [
                [
                    'Using class inheritance in a Twig component is forbidden, use traits for composition instead.',
                    15,
                    'Consider using traits to share common functionality between Twig components.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithInheritance.php'],
            [
                [
                    'Using class inheritance in a Twig component is forbidden, use traits for composition instead.',
                    15,
                    'Consider using traits to share common functionality between Twig components.',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/NotAComponent.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithoutInheritance.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ComponentUsingTrait.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithoutInheritance.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForbiddenInheritanceRule::class);
    }
}

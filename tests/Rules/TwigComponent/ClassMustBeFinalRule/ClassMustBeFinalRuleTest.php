<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ClassMustBeFinalRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassMustBeFinalRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassMustBeFinalRule>
 */
final class ClassMustBeFinalRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidNonFinalTwigComponent.php'],
            [
                [
                    'Twig component class must be final.',
                    9,
                    'Add the "final" keyword to the class declaration to prevent inheritance.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidNonFinalLiveComponent.php'],
            [
                [
                    'Twig component class must be final.',
                    9,
                    'Add the "final" keyword to the class declaration to prevent inheritance.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidAbstractTwigComponent.php'],
            [
                [
                    'Twig component class must be final, not abstract.',
                    9,
                    'Make the class final and use traits for composition instead of inheritance.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidAbstractLiveComponent.php'],
            [
                [
                    'Twig component class must be final, not abstract.',
                    9,
                    'Make the class final and use traits for composition instead of inheritance.',
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
            [__DIR__ . '/Fixture/ValidTwigComponent.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ValidLiveComponent.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ClassMustBeFinalRule::class);
    }
}

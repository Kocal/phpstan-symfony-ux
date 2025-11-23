<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ClassNameShouldNotEndWithComponentRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassNameShouldNotEndWithComponentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassNameShouldNotEndWithComponentRule>
 */
final class ClassNameShouldNotEndWithComponentRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidComponentName.php'],
            [
                [
                    'Twig component class "AlertComponent" should not end with "Component".',
                    10,
                    'Remove the "Component" suffix from the class name.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidLiveComponentName.php'],
            [
                [
                    'Twig component class "CounterComponent" should not end with "Component".',
                    10,
                    'Remove the "Component" suffix from the class name.',
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
            [__DIR__ . '/Fixture/ValidComponentName.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ValidLiveComponentName.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ClassNameShouldNotEndWithComponentRule::class);
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ClassNameMustNotEndWithComponentRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassNameMustNotEndWithComponentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ClassNameMustNotEndWithComponentRule>
 */
final class ClassNameMustNotEndWithComponentRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/AlertComponent.php'],
            [
                [
                    'Twig component class "AlertComponent" must not end with "Component".',
                    10,
                    'Remove the "Component" suffix from the class name.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/CounterComponent.php'],
            [
                [
                    'Twig component class "CounterComponent" must not end with "Component".',
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
        return self::getContainer()->getByType(ClassNameMustNotEndWithComponentRule::class);
    }
}

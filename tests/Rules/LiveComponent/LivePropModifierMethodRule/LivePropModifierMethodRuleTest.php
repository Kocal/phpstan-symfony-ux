<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropModifierMethodRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LivePropModifierMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LivePropModifierMethodRule>
 */
final class LivePropModifierMethodRuleTest extends RuleTestCase
{
    public function testModifierMethodNotFound(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ModifierMethodNotFound.php'],
            [
                [
                    'Property "query" references non-existent modifier method "nonExistentMethod()".',
                    13,
                    'Create the public method "nonExistentMethod()" in the component class.',
                ],
            ]
        );
    }

    public function testModifierMethodNotPublic(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ModifierMethodNotPublic.php'],
            [
                [
                    'Modifier method "modifyQueryProp()" referenced in property "query" must be public.',
                    16,
                    'Make the method "modifyQueryProp()" public.',
                ],
            ]
        );
    }

    public function testModifierMethodWrongParameterCount(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ModifierMethodWrongParameterCount.php'],
            [
                [
                    'Modifier method "modifyQueryProp()" must have 1 or 2 parameters (LiveProp and optionally string).',
                    16,
                    'The modifier method should have a LiveProp parameter and optionally a string parameter.',
                ],
            ]
        );
    }

    public function testModifierMethodWrongFirstParameterType(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ModifierMethodWrongFirstParameterType.php'],
            [
                [
                    'Modifier method "modifyQueryProp()" first parameter must be of type "Symfony\UX\LiveComponent\Attribute\LiveProp", got "string".',
                    16,
                    'Change the first parameter type to "Symfony\UX\LiveComponent\Attribute\LiveProp".',
                ],
            ]
        );
    }

    public function testModifierMethodWrongSecondParameterType(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ModifierMethodWrongSecondParameterType.php'],
            [
                [
                    'Modifier method "modifyQueryProp()" second parameter must be of type "string", got "int".',
                    16,
                    'Change the second parameter type to "string".',
                ],
            ]
        );
    }

    public function testModifierMethodWrongReturnType(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ModifierMethodWrongReturnType.php'],
            [
                [
                    'Modifier method "modifyQueryProp()" must return "Symfony\UX\LiveComponent\Attribute\LiveProp", got "string".',
                    16,
                    'Change the return type to ": Symfony\UX\LiveComponent\Attribute\LiveProp".',
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
            [__DIR__ . '/Fixture/ValidModifier.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ValidModifierWithOneParameter.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LivePropModifierMethodRule::class);
    }
}

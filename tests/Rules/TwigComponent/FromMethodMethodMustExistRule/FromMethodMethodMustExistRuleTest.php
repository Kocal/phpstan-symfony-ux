<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\FromMethodMethodMustExistRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\FromMethodMethodMustExistRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<FromMethodMethodMustExistRule>
 */
final class FromMethodMethodMustExistRuleTest extends RuleTestCase
{
    public function testNonExistentMethodTwigComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidTwigComponentNonExistentMethod.php'],
            [
                [
                    'Component "Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\FromMethodMethodMustExistRule\Fixture\InvalidTwigComponentNonExistentMethod" references non-existent method "nonExistentMethod()" in FromMethod.',
                    10,
                    'Create the public method "nonExistentMethod()" in the component class.',
                ],
            ]
        );
    }

    public function testNonExistentMethodLiveComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidLiveComponentNonExistentMethod.php'],
            [
                [
                    'Component "Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\FromMethodMethodMustExistRule\Fixture\InvalidLiveComponentNonExistentMethod" references non-existent method "nonExistentMethod()" in FromMethod.',
                    10,
                    'Create the public method "nonExistentMethod()" in the component class.',
                ],
            ]
        );
    }

    public function testNonExistentMethodPositionalTemplate(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidTwigComponentPositionalTemplate.php'],
            [
                [
                    'Component "Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\FromMethodMethodMustExistRule\Fixture\InvalidTwigComponentPositionalTemplate" references non-existent method "nonExistentMethod()" in FromMethod.',
                    10,
                    'Create the public method "nonExistentMethod()" in the component class.',
                ],
            ]
        );
    }

    public function testPrivateMethodTwigComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidTwigComponentPrivateMethod.php'],
            [
                [
                    'Method "getTemplate()" referenced in FromMethod must be public.',
                    13,
                    'Make the method "getTemplate()" public.',
                ],
            ]
        );
    }

    public function testPrivateMethodLiveComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidLiveComponentPrivateMethod.php'],
            [
                [
                    'Method "getTemplate()" referenced in FromMethod must be public.',
                    13,
                    'Make the method "getTemplate()" public.',
                ],
            ]
        );
    }

    public function testWrongReturnTypeTwigComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidTwigComponentWrongReturnType.php'],
            [
                [
                    'Method "getTemplate()" referenced in FromMethod must return string, got "array".',
                    13,
                    'Change the return type of "getTemplate()" to string.',
                ],
            ]
        );
    }

    public function testWrongReturnTypeLiveComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidLiveComponentWrongReturnType.php'],
            [
                [
                    'Method "getTemplate()" referenced in FromMethod must return string, got "array".',
                    13,
                    'Change the return type of "getTemplate()" to string.',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse([__DIR__ . '/Fixture/NotAComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidTwigComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidLiveComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/TwigComponentWithStringTemplate.php'], []);
        $this->analyse([__DIR__ . '/Fixture/LiveComponentWithStringTemplate.php'], []);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(FromMethodMethodMustExistRule::class);
    }
}

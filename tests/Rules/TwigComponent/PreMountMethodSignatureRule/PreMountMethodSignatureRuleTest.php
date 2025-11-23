<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PreMountMethodSignatureRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<PreMountMethodSignatureRule>
 */
final class PreMountMethodSignatureRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidNotPublic.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must be public.',
                    13,
                    'Change the method visibility to public.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidNoReturnType.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must have a return type of "array".',
                    13,
                    'Add ": array" return type to the method.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidWrongReturnType.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must have a return type of "array".',
                    14,
                    'Change the return type to ": array".',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidNoParameter.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must have exactly one parameter of type "array".',
                    13,
                    'The method should have exactly one parameter: "array $data".',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidTooManyParameters.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must have exactly one parameter of type "array".',
                    13,
                    'The method should have exactly one parameter: "array $data".',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidWithNullableReturnType.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must have a return type of "array".',
                    14,
                    'Change the return type to ": array".',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidWrongParameterType.php'],
            [
                [
                    'Method "preMount" with #[PreMount] attribute must have a parameter of type "array".',
                    14,
                    'Change the parameter type to "array".',
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
        return self::getContainer()->getByType(PreMountMethodSignatureRule::class);
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PublicPropertiesMustBeCamelCaseRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PublicPropertiesMustBeCamelCaseRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<PublicPropertiesMustBeCamelCaseRule>
 */
final class PublicPropertiesMustBeCamelCaseRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithSnakeCaseProperty.php'],
            [
                [
                    'Public property "user_name" in a Twig component must be in camelCase.',
                    12,
                    'Consider renaming "user_name" to "userName".',
                ],
                [
                    'Public property "is_active" in a Twig component must be in camelCase.',
                    14,
                    'Consider renaming "is_active" to "isActive".',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithPascalCaseProperty.php'],
            [
                [
                    'Public property "UserName" in a Twig component must be in camelCase.',
                    12,
                    'Consider renaming "UserName" to "userName".',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithSnakeCaseProperty.php'],
            [
                [
                    'Public property "user_name" in a Twig component must be in camelCase.',
                    12,
                    'Consider renaming "user_name" to "userName".',
                ],
                [
                    'Public property "is_active" in a Twig component must be in camelCase.',
                    14,
                    'Consider renaming "is_active" to "isActive".',
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
            [__DIR__ . '/Fixture/ComponentWithCamelCaseProperties.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithPrivateNonCamelCaseProperties.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithCamelCaseProperties.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(PublicPropertiesMustBeCamelCaseRule::class);
    }
}

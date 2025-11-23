<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenAttributesPropertyRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenAttributesPropertyRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ForbiddenAttributesPropertyRule>
 */
final class ForbiddenAttributesPropertyRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithAttributesProperty.php'],
            [
                [
                    'Using property "attributes" in a Twig component is forbidden, it may lead to confusion with the default "attributes" Twig variable.',
                    12,
                    'Consider renaming or removing this property to avoid conflicts with the Twig component attributes.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithCustomAttributesProperty.php'],
            [
                [
                    'Using property "customAttributes" in a Twig component is forbidden, it may lead to confusion with the "customAttributes" attribute defined in #[AsTwigComponent].',
                    12,
                    'Consider renaming or removing this property to avoid conflicts with the Twig component attributes.',
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
            [__DIR__ . '/Fixture/ComponentWithNoAttributesProperty.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForbiddenAttributesPropertyRule::class);
    }
}

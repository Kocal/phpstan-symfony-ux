<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<MethodsShouldBePublicOrPrivateRule>
 */
final class MethodsShouldBePublicOrPrivateRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithProtectedMethod.php'],
            [
                [
                    'Method "protectedMethod()" in a Twig component should not be protected.',
                    14,
                    'Twig component methods should be either public or private, not protected.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithProtectedMethod.php'],
            [
                [
                    'Method "protectedMethod()" in a Twig component should not be protected.',
                    14,
                    'Twig component methods should be either public or private, not protected.',
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
            [__DIR__ . '/Fixture/ComponentWithPublicAndPrivateMethods.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithPublicAndPrivateMethods.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(MethodsShouldBePublicOrPrivateRule::class);
    }
}

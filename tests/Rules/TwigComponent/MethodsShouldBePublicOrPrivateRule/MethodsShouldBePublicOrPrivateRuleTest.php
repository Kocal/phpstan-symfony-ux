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
                    'Method "protectedMethod()" in a Twig component must not be protected.',
                    14,
                    'Twig component methods should be either public or private, not protected.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithProtectedMethod.php'],
            [
                [
                    'Method "protectedMethod()" in a Twig component must not be protected.',
                    14,
                    'Twig component methods should be either public or private, not protected.',
                ],
            ]
        );

        // Protected concrete (non-abstract) methods from traits should trigger an error
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithProtectedConcreteMethodFromTrait.php'],
            [
                [
                    'Method "protectedConcreteTraitMethod()" in a Twig component must not be protected.',
                    12,
                    'Twig component methods should be either public or private, not protected.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithProtectedConcreteMethodFromTrait.php'],
            [
                [
                    'Method "protectedConcreteTraitMethod()" in a Twig component must not be protected.',
                    12,
                    'Twig component methods should be either public or private, not protected.',
                ],
            ]
        );

        // When two traits define the same method, should report only one error
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithDuplicateTraitMethods.php'],
            [
                [
                    'Method "duplicateConcreteMethod()" in a Twig component must not be protected.',
                    12,
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

        // Protected methods implementing abstract trait methods should not trigger an error
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithProtectedMethodFromTrait.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithProtectedMethodFromTrait.php'],
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

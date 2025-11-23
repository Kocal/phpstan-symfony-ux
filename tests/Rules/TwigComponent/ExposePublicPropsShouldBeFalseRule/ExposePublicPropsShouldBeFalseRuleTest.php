<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExposePublicPropsShouldBeFalseRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExposePublicPropsShouldBeFalseRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ExposePublicPropsShouldBeFalseRule>
 */
final class ExposePublicPropsShouldBeFalseRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithoutExposePublicProps.php'],
            [
                [
                    'The #[AsTwigComponent] attribute must have its "exposePublicProps" parameter set to false.',
                    9,
                    'Set "exposePublicProps" to false in the #[AsTwigComponent] attribute.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithExposePublicPropsTrue.php'],
            [
                [
                    'The #[AsTwigComponent] attribute must have its "exposePublicProps" parameter set to false.',
                    9,
                    'Set "exposePublicProps" to false in the #[AsTwigComponent] attribute.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithExposePublicPropsTrue.php'],
            [
                [
                    'The #[AsLiveComponent] attribute must have its "exposePublicProps" parameter set to false.',
                    9,
                    'Set "exposePublicProps" to false in the #[AsLiveComponent] attribute.',
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
            [__DIR__ . '/Fixture/ComponentWithExposePublicPropsFalse.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithExposePublicPropsFalse.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ExposePublicPropsShouldBeFalseRule::class);
    }
}

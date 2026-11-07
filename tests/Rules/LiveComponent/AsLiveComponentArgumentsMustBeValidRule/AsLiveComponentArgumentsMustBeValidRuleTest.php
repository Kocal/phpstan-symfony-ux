<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<AsLiveComponentArgumentsMustBeValidRule>
 */
final class AsLiveComponentArgumentsMustBeValidRuleTest extends RuleTestCase
{
    public function testInvalidMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidMethod.php'],
            [
                [
                    'The "method" parameter of the #[AsLiveComponent] attribute must be "get" or "post", got "put".',
                    9,
                    'Set the "method" parameter to "get" or "post".',
                ],
            ]
        );
    }

    public function testInvalidFetchCredentials(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidFetchCredentials.php'],
            [
                [
                    'The "fetchCredentials" parameter of the #[AsLiveComponent] attribute must be "same-origin", "include" or "omit", got "same_origin".',
                    9,
                    'Set the "fetchCredentials" parameter to "same-origin", "include" or "omit", or remove it to use the "live_component.fetch_credentials" configuration.',
                ],
            ]
        );
    }

    public function testInvalidPositionalArguments(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidPositionalArguments.php'],
            [
                [
                    'The "method" parameter of the #[AsLiveComponent] attribute must be "get" or "post", got "PATCH".',
                    9,
                    'Set the "method" parameter to "get" or "post".',
                ],
                [
                    'The "fetchCredentials" parameter of the #[AsLiveComponent] attribute must be "same-origin", "include" or "omit", got "all".',
                    9,
                    'Set the "fetchCredentials" parameter to "same-origin", "include" or "omit", or remove it to use the "live_component.fetch_credentials" configuration.',
                ],
            ]
        );
    }

    public function testInvalidPositionalArgumentsWithDeprecatedCsrf(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidPositionalArgumentsWithDeprecatedCsrf.php'],
            [
                [
                    'The "method" parameter of the #[AsLiveComponent] attribute must be "get" or "post", got "patch".',
                    9,
                    'Set the "method" parameter to "get" or "post".',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse([__DIR__ . '/Fixture/NotAComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidLiveComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidPositionalArguments.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidPositionalArgumentsWithDeprecatedCsrf.php'], []);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(AsLiveComponentArgumentsMustBeValidRule::class);
    }
}

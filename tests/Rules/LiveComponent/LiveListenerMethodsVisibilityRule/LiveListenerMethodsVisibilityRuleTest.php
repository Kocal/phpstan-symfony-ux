<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveListenerMethodsVisibilityRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsVisibilityRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LiveListenerMethodsVisibilityRule>
 */
final class LiveListenerMethodsVisibilityRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/PrivateLiveListener.php'],
            [
                [
                    'LiveListener method "onAnotherEvent()" must be public.',
                    13,
                    'Change the method visibility to public.',
                ],
                [
                    'LiveListener method "onSomeEvent()" must be public.',
                    18,
                    'Change the method visibility to public.',
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
            [__DIR__ . '/Fixture/PublicLiveListener.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/NoLiveListener.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LiveListenerMethodsVisibilityRule::class);
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveListenerMethodsShouldBePublicRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsShouldBePublicRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LiveListenerMethodsShouldBePublicRule>
 */
final class LiveListenerMethodsShouldBePublicRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/PrivateLiveListener.php'],
            [
                [
                    'LiveListener method "onAnotherEvent()" should be public.',
                    13,
                    'Change the method visibility to public.',
                ],
                [
                    'LiveListener method "onSomeEvent()" should be public.',
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
        return self::getContainer()->getByType(LiveListenerMethodsShouldBePublicRule::class);
    }
}

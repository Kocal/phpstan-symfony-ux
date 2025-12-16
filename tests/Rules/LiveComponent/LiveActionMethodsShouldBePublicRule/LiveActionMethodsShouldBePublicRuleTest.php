<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveActionMethodsShouldBePublicRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveActionMethodsShouldBePublicRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LiveActionMethodsShouldBePublicRule>
 */
final class LiveActionMethodsShouldBePublicRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithPrivateLiveAction.php'],
            [
                [
                    'LiveAction method "save()" must be public.',
                    15,
                    'Methods annotated with #[LiveAction] must be public to be accessible as component actions.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithProtectedLiveAction.php'],
            [
                [
                    'LiveAction method "delete()" must be public.',
                    15,
                    'Methods annotated with #[LiveAction] must be public to be accessible as component actions.',
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
            [__DIR__ . '/Fixture/LiveComponentWithPublicLiveAction.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithoutLiveAction.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LiveActionMethodsShouldBePublicRule::class);
    }
}

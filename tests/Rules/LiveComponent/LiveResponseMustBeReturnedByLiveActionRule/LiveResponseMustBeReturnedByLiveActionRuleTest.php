<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LiveResponseMustBeReturnedByLiveActionRule>
 */
final class LiveResponseMustBeReturnedByLiveActionRuleTest extends RuleTestCase
{
    public function testDefaultActionReturningLiveResponse(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidDefaultAction.php'],
            [
                [
                    'Default action "__invoke()" must not return a LiveResponse.',
                    13,
                    'The default action runs on every re-render, return the LiveResponse from a #[LiveAction] or #[LiveListener] method instead.',
                ],
            ]
        );
    }

    public function testCustomDefaultActionReturningLiveResponse(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidCustomDefaultAction.php'],
            [
                [
                    'Default action "refresh()" must not return a LiveResponse.',
                    15,
                    'The default action runs on every re-render, return the LiveResponse from a #[LiveAction] or #[LiveListener] method instead.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidPositionalDefaultAction.php'],
            [
                [
                    'Default action "refresh()" must not return a LiveResponse.',
                    15,
                    'The default action runs on every re-render, return the LiveResponse from a #[LiveAction] or #[LiveListener] method instead.',
                ],
            ]
        );
    }

    public function testPublicMethodReturningLiveResponseWithoutLiveAction(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidNotALiveAction.php'],
            [
                [
                    'Method "export()" returning a LiveResponse must be a LiveAction or a LiveListener.',
                    13,
                    'Add the #[LiveAction] attribute to "export()", or make it private if it only builds the LiveResponse for another action.',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse([__DIR__ . '/Fixture/NotAComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidLiveComponent.php'], []);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LiveResponseMustBeReturnedByLiveActionRule::class);
    }
}

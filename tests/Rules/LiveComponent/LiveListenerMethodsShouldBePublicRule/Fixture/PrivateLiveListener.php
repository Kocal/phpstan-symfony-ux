<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveListenerMethodsShouldBePublicRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

#[AsLiveComponent]
final class PrivateLiveListener
{
    #[LiveListener('another.event')]
    protected function onAnotherEvent(): void
    {
    }

    #[LiveListener('some.event')]
    private function onSomeEvent(): void
    {
    }
}

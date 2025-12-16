<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveListenerMethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

#[AsLiveComponent]
final class PublicLiveListener
{
    #[LiveListener('some.event')]
    public function onSomeEvent(): void
    {
    }

    #[LiveListener('another.event')]
    public function onAnotherEvent(): void
    {
    }
}

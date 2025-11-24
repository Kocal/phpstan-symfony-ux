<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveActionMethodsShouldBePublicRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class LiveComponentWithPublicLiveAction
{
    public string $title = '';

    #[LiveAction]
    public function submit(): void
    {
    }

    #[LiveAction]
    public function cancel(): void
    {
    }
}

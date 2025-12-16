<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveActionMethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class LiveComponentWithPrivateLiveAction
{
    public string $name = '';

    #[LiveAction]
    private function save(): void
    {
    }
}

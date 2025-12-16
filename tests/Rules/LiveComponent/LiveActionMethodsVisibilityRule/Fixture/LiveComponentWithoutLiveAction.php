<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveActionMethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LiveComponentWithoutLiveAction
{
    public string $count = '0';

    public function increment(): void
    {
    }

    private function helperMethod(): void
    {
    }
}

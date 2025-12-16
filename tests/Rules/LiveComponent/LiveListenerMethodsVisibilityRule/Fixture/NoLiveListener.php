<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveListenerMethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class NoLiveListener
{
    protected function someProtectedMethod(): void
    {
    }

    private function somePrivateMethod(): void
    {
    }
}

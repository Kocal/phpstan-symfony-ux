<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LiveComponentWithPublicAndPrivateMethods
{
    public string $name = '';

    public function publicMethod(): void
    {
    }

    private function privateMethod(): void
    {
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LiveComponentWithProtectedMethod
{
    public string $name = '';

    protected function protectedMethod(): void
    {
    }
}

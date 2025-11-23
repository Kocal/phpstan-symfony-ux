<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule\Fixture;

final class NotAComponent
{
    public string $name = '';

    protected function protectedMethod(): void
    {
    }
}

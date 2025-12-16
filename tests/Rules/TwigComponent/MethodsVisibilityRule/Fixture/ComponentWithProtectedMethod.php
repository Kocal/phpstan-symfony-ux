<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ComponentWithProtectedMethod
{
    public string $name = '';

    protected function protectedMethod(): void
    {
    }
}

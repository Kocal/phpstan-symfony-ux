<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ComponentWithPublicAndPrivateMethods
{
    public string $name = '';

    public function publicMethod(): void
    {
    }

    private function privateMethod(): void
    {
    }
}

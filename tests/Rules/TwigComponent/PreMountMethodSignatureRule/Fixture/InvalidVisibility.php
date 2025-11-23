<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
final class InvalidVisibility
{
    #[PreMount]
    protected function preMountProtected(): void
    {
    }

    #[PreMount]
    private function preMountPrivate(): void
    {
    }
}

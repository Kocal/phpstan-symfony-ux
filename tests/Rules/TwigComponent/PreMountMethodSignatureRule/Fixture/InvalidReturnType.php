<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
final class InvalidReturnType
{
    #[PreMount]
    public function preMountStringReturn(): string
    {
        return '';
    }

    #[PreMount]
    public function preMountIntReturn(): int
    {
        return 0;
    }

    #[PreMount]
    public function preMountBoolReturn(): bool
    {
        return true;
    }
}

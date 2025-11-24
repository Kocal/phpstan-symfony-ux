<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent]
final class InvalidLiveComponent
{
    #[PreMount]
    public function preMountWrongType(string $data): void
    {
    }

    #[PreMount]
    public function preMountWrongReturn(): string
    {
        return '';
    }

    #[PreMount]
    private function preMountPrivate(): void
    {
    }
}

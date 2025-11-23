<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
final class InvalidLiveComponent
{
    #[PostMount]
    public function postMountWrongType(string $data): void
    {
    }

    #[PostMount]
    public function postMountWrongReturn(): string
    {
        return '';
    }

    #[PostMount]
    private function postMountPrivate(): void
    {
    }
}

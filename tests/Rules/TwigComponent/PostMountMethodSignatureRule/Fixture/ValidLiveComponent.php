<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
final class ValidLiveComponent
{
    // Valid: public, no parameters, returns void
    #[PostMount]
    public function postMount1(): void
    {
    }

    // Valid: public, array parameter, returns void
    #[PostMount]
    public function postMount2(array $data): void
    {
    }

    // Valid: public, no parameters, returns array
    #[PostMount]
    public function postMount3(): array
    {
        return [];
    }

    // Valid: public, array parameter, returns array
    #[PostMount]
    public function postMount4(array $data): array
    {
        return [];
    }
}

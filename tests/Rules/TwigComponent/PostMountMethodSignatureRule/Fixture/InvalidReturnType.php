<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class InvalidReturnType
{
    #[PostMount]
    public function postMountStringReturn(): string
    {
        return '';
    }

    #[PostMount]
    public function postMountIntReturn(): int
    {
        return 0;
    }

    #[PostMount]
    public function postMountBoolReturn(): bool
    {
        return true;
    }
}

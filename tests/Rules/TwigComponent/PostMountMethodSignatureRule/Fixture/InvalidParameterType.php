<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class InvalidParameterType
{
    #[PostMount]
    public function postMountWrongType(string $data): void
    {
    }

    #[PostMount]
    public function postMountIntType(int $data): void
    {
    }
}

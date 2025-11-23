<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
final class InvalidParameterType
{
    #[PreMount]
    public function preMountWrongType(string $data): void
    {
    }

    #[PreMount]
    public function preMountIntType(int $data): void
    {
    }
}

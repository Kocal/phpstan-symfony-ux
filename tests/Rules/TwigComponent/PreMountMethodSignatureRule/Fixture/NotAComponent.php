<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\PreMount;

final class NotAComponent
{
    #[PreMount]
    public function preMount(array $data): array
    {
        return $data;
    }
}

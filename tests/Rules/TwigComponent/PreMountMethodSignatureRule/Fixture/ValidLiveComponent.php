<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PreMountMethodSignatureRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent]
final class ValidLiveComponent
{
    #[PreMount]
    public function preMount(array $data): array
    {
        return $data;
    }
}

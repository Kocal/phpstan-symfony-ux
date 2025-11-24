<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\PostMount;

final class NotAComponent
{
    #[PostMount]
    private function postMount(string $data): string
    {
        return '';
    }
}

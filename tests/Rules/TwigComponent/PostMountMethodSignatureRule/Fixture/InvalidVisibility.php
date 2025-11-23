<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class InvalidVisibility
{
    #[PostMount]
    protected function postMountProtected(): void
    {
    }

    #[PostMount]
    private function postMountPrivate(): void
    {
    }
}

<?php

declare(strict_types=1);

namespace Symfony\UX\LiveComponent;

// symfony/ux-live-component 3.5+ ships this class but requires PHP 8.4, while tests run on PHP 8.2
final class LiveResponse
{
    public static function remove(): self
    {
        return new self();
    }

    public static function downloadUrl(string $url): self
    {
        return new self();
    }
}

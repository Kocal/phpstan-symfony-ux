<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenReadonlyRule\Fixture;

final readonly class NotAComponent
{
    public function __construct(
        public string $message,
    ) {
    }
}

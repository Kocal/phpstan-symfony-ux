<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenReadonlyRule\Fixture;

use Psr\Log\LoggerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class ValidLiveComponent
{
    public string $message;

    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }
}

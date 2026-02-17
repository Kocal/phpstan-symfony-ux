<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenReadonlyRule\Fixture;

use Psr\Log\LoggerInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ValidTwigComponent
{
    public string $message;

    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }
}

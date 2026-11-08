<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\LiveResponse;

#[AsLiveComponent(defaultAction: 'refresh()')]
final class InvalidCustomDefaultAction
{
    public bool $dismissed = false;

    public function refresh(): ?LiveResponse
    {
        return $this->dismissed ? LiveResponse::remove() : null;
    }
}

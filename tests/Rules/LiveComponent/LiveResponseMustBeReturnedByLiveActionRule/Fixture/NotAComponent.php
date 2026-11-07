<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule\Fixture;

use Symfony\UX\LiveComponent\LiveResponse;

final class NotAComponent
{
    public function __invoke(): LiveResponse
    {
        return LiveResponse::remove();
    }
}

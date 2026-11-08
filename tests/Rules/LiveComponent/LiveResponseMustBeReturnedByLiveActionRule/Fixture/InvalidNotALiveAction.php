<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\LiveResponse;

#[AsLiveComponent]
final class InvalidNotALiveAction
{
    public function export(): LiveResponse
    {
        return LiveResponse::downloadUrl('/export.csv');
    }
}

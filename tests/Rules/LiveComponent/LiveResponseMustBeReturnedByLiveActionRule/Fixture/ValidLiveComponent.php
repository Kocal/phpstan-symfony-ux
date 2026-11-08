<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveResponse;

#[AsLiveComponent]
final class ValidLiveComponent
{
    use DefaultActionTrait;

    #[LiveAction]
    public function export(): LiveResponse
    {
        return $this->download();
    }

    #[LiveListener('notification.read')]
    public function dismiss(): LiveResponse
    {
        return LiveResponse::remove();
    }

    private function download(): LiveResponse
    {
        return LiveResponse::downloadUrl('/export.csv');
    }
}

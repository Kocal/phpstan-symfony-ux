<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveResponseMustBeReturnedByLiveActionRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\LiveResponse;

#[AsLiveComponent('InvalidPositionalDefaultAction', 'components/InvalidPositionalDefaultAction.html.twig', 'refresh')]
final class InvalidPositionalDefaultAction
{
    public bool $dismissed = false;

    public function refresh(): LiveResponse|null
    {
        return $this->dismissed ? LiveResponse::remove() : null;
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(method: 'GET', fetchCredentials: 'include')]
final class ValidLiveComponent
{
}

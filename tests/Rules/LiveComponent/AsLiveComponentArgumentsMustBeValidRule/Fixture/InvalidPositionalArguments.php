<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('InvalidPositionalArguments', null, null, true, 'attributes', 'ux_live_component', 'PATCH', 1, 'all')]
final class InvalidPositionalArguments
{
}

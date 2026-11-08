<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('InvalidPositionalArgumentsWithDeprecatedCsrf', null, null, true, 'attributes', false, 'ux_live_component', 'patch')]
final class InvalidPositionalArgumentsWithDeprecatedCsrf
{
}

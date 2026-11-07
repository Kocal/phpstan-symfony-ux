<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('ValidPositionalArgumentsWithDeprecatedCsrf', null, null, true, 'attributes', true, 'ux_live_component', 'get', 1)]
final class ValidPositionalArgumentsWithDeprecatedCsrf
{
}

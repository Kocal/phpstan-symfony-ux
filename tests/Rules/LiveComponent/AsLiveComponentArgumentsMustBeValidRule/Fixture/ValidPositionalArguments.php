<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\AsLiveComponentArgumentsMustBeValidRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('ValidPositionalArguments', null, null, true, 'attributes', 'ux_live_component', 'post', 1, null)]
final class ValidPositionalArguments
{
}

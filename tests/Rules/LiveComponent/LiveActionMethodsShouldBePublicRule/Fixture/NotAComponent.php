<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveActionMethodsShouldBePublicRule\Fixture;

class NotAComponent
{
    private function save(): void
    {
    }
}

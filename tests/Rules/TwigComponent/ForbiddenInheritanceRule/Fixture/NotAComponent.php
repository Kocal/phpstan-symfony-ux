<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenInheritanceRule\Fixture;

abstract class BaseClass
{
    public string $name;
}

final class NotAComponent extends BaseClass
{
}

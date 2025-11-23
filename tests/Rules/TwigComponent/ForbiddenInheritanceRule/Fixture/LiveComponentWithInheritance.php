<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenInheritanceRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

abstract class BaseComponent
{
    public string $name;
}

#[AsLiveComponent]
final class LiveComponentWithInheritance extends BaseComponent
{
}

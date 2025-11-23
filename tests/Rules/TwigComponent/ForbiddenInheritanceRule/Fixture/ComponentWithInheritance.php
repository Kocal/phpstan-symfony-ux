<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenInheritanceRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

abstract class BaseComponent
{
    public string $name;
}

#[AsTwigComponent]
final class ComponentWithInheritance extends BaseComponent
{
}

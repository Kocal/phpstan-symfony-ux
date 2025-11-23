<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenInheritanceRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

trait CommonComponentTrait
{
    public string $name;
}

#[AsTwigComponent]
final class ComponentUsingTrait
{
    use CommonComponentTrait;
}

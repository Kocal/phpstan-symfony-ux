<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PublicPropertiesMustBeCamelCaseRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ComponentWithCamelCaseProperties
{
    public string $userName;

    public bool $isActive;

    public int $count;
}

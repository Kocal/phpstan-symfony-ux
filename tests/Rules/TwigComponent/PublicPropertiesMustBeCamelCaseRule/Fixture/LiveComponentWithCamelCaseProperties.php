<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PublicPropertiesMustBeCamelCaseRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LiveComponentWithCamelCaseProperties
{
    public string $userName;

    public bool $isActive;
}

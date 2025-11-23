<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PublicPropertiesShouldBeCamelCaseRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ComponentWithPrivateNonCamelCaseProperties
{
    public string $validProperty;

    private string $user_name;

    private bool $is_active;
}

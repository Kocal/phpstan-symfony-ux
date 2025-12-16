<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PublicPropertiesMustBeCamelCaseRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ComponentWithSnakeCaseProperty
{
    public string $user_name;

    public bool $is_active;
}

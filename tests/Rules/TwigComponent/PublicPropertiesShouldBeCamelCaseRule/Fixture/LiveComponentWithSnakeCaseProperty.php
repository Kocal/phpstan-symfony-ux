<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PublicPropertiesShouldBeCamelCaseRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
final class LiveComponentWithSnakeCaseProperty
{
    public string $user_name;

    public bool $is_active;
}

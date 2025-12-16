<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
class LiveComponentWithProtectedConcreteMethodFromTrait
{
    use TestTraitWithConcreteMethod;

    public string $name = '';

    // protectedConcreteTraitMethod() comes from the trait and is NOT abstract - SHOULD trigger an error
}

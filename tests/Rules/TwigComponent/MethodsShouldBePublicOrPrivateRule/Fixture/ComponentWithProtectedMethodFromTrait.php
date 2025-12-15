<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ComponentWithProtectedMethodFromTrait
{
    use TestTraitWithAbstractMethod;

    public string $name = '';

    protected function protectedAbstractTraitMethod(): void
    {
        // Implementation of abstract method from trait - should NOT trigger an error
    }
}

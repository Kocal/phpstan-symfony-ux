<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
class LiveComponentWithProtectedMethodFromTrait
{
    use TestTraitWithAbstractMethod;

    public string $name = '';

    protected function protectedAbstractTraitMethod(): void
    {
        // Implementation of abstract method from trait - should NOT trigger an error
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ComponentWithDuplicateTraitMethods
{
    use TraitA;
    use TraitB;

    public string $name = '';

    protected function duplicateAbstractMethod(): void
    {
        // Implementation of abstract method from both traits - should NOT trigger an error
    }

    // duplicateConcreteMethod() comes from both traits - should trigger only ONE error
}

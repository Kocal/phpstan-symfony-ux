<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule\Fixture;

trait TestTraitWithAbstractMethod
{
    abstract protected function protectedAbstractTraitMethod(): void;
}

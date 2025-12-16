<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

trait TestTraitWithAbstractMethod
{
    abstract protected function protectedAbstractTraitMethod(): void;
}

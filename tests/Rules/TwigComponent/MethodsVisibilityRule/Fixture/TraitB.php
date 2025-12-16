<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MethodsVisibilityRule\Fixture;

trait TraitB
{
    abstract protected function duplicateAbstractMethod(): void;

    protected function duplicateConcreteMethod(): void
    {
    }
}

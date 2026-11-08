<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\FromMethodMethodMustExistRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\FromMethod;

#[AsLiveComponent(template: new FromMethod('getTemplate'))]
final class InvalidLiveComponentWrongReturnType
{
    public function getTemplate(): array
    {
        return ['components/template.html.twig'];
    }
}

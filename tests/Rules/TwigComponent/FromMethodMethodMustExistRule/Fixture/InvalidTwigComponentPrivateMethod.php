<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\FromMethodMethodMustExistRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\FromMethod;

#[AsTwigComponent(template: new FromMethod('getTemplate'))]
final class InvalidTwigComponentPrivateMethod
{
    private function getTemplate(): string
    {
        return 'components/template.html.twig';
    }
}

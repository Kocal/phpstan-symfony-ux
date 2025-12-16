<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExposePublicPropsMustBeFalseRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(exposePublicProps: true)]
final class ComponentWithExposePublicPropsTrue
{
    public string $name;
}

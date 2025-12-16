<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExposePublicPropsMustBeFalseRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(exposePublicProps: false)]
final class ComponentWithExposePublicPropsFalse
{
    public string $name;
}

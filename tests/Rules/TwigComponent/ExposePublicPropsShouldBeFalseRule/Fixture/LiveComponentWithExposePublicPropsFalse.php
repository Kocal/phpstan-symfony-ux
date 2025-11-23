<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExposePublicPropsShouldBeFalseRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(exposePublicProps: false)]
final class LiveComponentWithExposePublicPropsFalse
{
    public string $name;
}

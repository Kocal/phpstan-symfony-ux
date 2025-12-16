<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExposePublicPropsMustBeFalseRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(exposePublicProps: true)]
final class LiveComponentWithExposePublicPropsTrue
{
    public string $name;
}

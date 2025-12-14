<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\LiveProp;

final class NotAComponent
{
    #[LiveProp(hydrateWith: 'hydrateData', dehydrateWith: 'dehydrateData')]
    public $data;
}

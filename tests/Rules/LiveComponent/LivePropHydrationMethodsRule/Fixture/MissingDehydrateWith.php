<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class MissingDehydrateWith
{
    #[LiveProp(hydrateWith: 'hydrateData')]
    public array $data;

    public function hydrateData(array $data): array
    {
        return $data;
    }
}

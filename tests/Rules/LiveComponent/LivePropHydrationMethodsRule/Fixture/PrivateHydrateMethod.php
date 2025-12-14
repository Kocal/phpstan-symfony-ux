<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class PrivateHydrateMethod
{
    #[LiveProp(hydrateWith: 'hydrateData', dehydrateWith: 'dehydrateData')]
    public array $data;

    public function dehydrateData(array $data): array
    {
        return $data;
    }

    private function hydrateData(array $data): array
    {
        return $data;
    }
}

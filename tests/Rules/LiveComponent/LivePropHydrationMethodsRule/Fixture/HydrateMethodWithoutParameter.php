<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class HydrateMethodWithoutParameter
{
    #[LiveProp(hydrateWith: 'hydrateData', dehydrateWith: 'dehydrateData')]
    public array $data;

    public function hydrateData(): array
    {
        return [];
    }

    public function dehydrateData(array $data): array
    {
        return $data;
    }
}

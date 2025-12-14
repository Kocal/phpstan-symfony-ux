<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class ProtectedDehydrateMethod
{
    #[LiveProp(hydrateWith: 'hydrateData', dehydrateWith: 'dehydrateData')]
    public array $data;

    public function hydrateData(array $data): array
    {
        return $data;
    }

    protected function dehydrateData(array $data): array
    {
        return $data;
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

class DataObject
{
    public function __construct(
        public string $value,
    ) {
    }
}

#[AsLiveComponent]
final class ValidComplexTypes
{
    #[LiveProp(hydrateWith: 'hydrateData', dehydrateWith: 'dehydrateData')]
    public DataObject $data;

    public function hydrateData(array $data): DataObject
    {
        return new DataObject($data['value']);
    }

    public function dehydrateData(DataObject $data): array
    {
        return [
            'value' => $data->value,
        ];
    }
}

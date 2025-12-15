<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropModifierMethodRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class ModifierMethodWrongSecondParameterType
{
    #[LiveProp(modifier: 'modifyQueryProp')]
    public string $query;

    public function modifyQueryProp(LiveProp $liveProp, int $name): LiveProp
    {
        return $liveProp;
    }
}

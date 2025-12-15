<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropModifierMethodRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class ValidModifier
{
    #[LiveProp(modifier: 'modifyQueryProp')]
    public string $query;

    #[LiveProp]
    public ?string $alias = null;

    public function modifyQueryProp(LiveProp $liveProp, string $name): LiveProp
    {
        return $liveProp;
    }
}

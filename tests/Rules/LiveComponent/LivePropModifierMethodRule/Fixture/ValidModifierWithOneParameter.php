<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropModifierMethodRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class ValidModifierWithOneParameter
{
    #[LiveProp(modifier: 'modifyProp')]
    public string $value;

    public function modifyProp(LiveProp $liveProp): LiveProp
    {
        return $liveProp;
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LiveListenerMethodsVisibilityRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\LiveListener;

final class NotAComponent
{
    #[LiveListener('some.event')]
    private function onSomeEvent(): void
    {
    }
}

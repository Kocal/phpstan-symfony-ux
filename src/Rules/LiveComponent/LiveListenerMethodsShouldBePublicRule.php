<?php

declare(strict_types=1);

/**
 * @deprecated since 1.x, use LiveListenerMethodsVisibilityRule instead. Will be removed in 2.0.
 */
class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsVisibilityRule::class,
    'Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsShouldBePublicRule'
);

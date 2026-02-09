<?php

declare(strict_types=1);

/**
 * @deprecated since 1.x, use ExposePublicPropsMustBeFalseRule instead. Will be removed in 2.0.
 */
class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExposePublicPropsMustBeFalseRule::class,
    'Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExposePublicPropsShouldBeFalseRule'
);

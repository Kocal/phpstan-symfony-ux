<?php

declare(strict_types=1);

/**
 * Deprecated class aliases for backward compatibility.
 * These aliases will be removed in version 2.0.
 */

// LiveComponent rules
class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveActionMethodsVisibilityRule::class,
    \Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveActionMethodsShouldBePublicRule::class
);

class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsVisibilityRule::class,
    \Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsShouldBePublicRule::class
);

// TwigComponent rules
class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MethodsVisibilityRule::class,
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule::class
);

class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PublicPropertiesMustBeCamelCaseRule::class,
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PublicPropertiesShouldBeCamelCaseRule::class
);

class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassNameMustNotEndWithComponentRule::class,
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassNameShouldNotEndWithComponentRule::class
);

class_alias(
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExposePublicPropsMustBeFalseRule::class,
    \Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExposePublicPropsShouldBeFalseRule::class
);

<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()
    ->withPreparedSets(psr12: true, common: true)
    ->withSkip([
        ProtectedToPrivateFixer::class => [
            __DIR__ . '/tests/Rules/TwigComponent/MethodsShouldBePublicOrPrivateRule/Fixture/*',
        ],
    ])
;

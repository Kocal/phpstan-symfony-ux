<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenClassPropertyRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenClassPropertyRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

final class ForbiddenClassPropertyRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithClassProperty.php'],
            [
                [
                    'Using a "class" property in a Twig component is forbidden, it is considered as an anti-pattern.',
                    12,
                    'Consider using {{ attributes }} to automatically render unknown properties as HTML attributes, such as "class". Learn more at https://symfony.com/bundles/ux-twig-component/current/index.html#component-attributes.',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/NotAComponent.php'],
            []
        );
        $this->analyse(
            [__DIR__ . '/Fixture/ComponentWithNoClassProperty.php'],
            []
        );
    }

    protected function getRule(): Rule
    {
        return new ForbiddenClassPropertyRule();
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ForbiddenReadonlyRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenReadonlyRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ForbiddenReadonlyRule>
 */
final class ForbiddenReadonlyRuleTest extends RuleTestCase
{
    public function testReadonlyClassViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ReadonlyTwigComponent.php'],
            [
                [
                    'Twig component class must not be readonly.',
                    9,
                    'Remove the "readonly" keyword from the class declaration. Twig components need mutable properties to receive props.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ReadonlyLiveComponent.php'],
            [
                [
                    'Twig component class must not be readonly.',
                    9,
                    'Remove the "readonly" keyword from the class declaration. Twig components need mutable properties to receive props.',
                ],
            ]
        );
    }

    public function testReadonlyPropertyViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/TwigComponentWithReadonlyProperty.php'],
            [
                [
                    'Property "$message" must not be readonly.',
                    12,
                    'Remove the "readonly" keyword from the property declaration. Only constructor-promoted properties (injected services) can be readonly.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/LiveComponentWithReadonlyProperty.php'],
            [
                [
                    'Property "$message" must not be readonly.',
                    12,
                    'Remove the "readonly" keyword from the property declaration. Only constructor-promoted properties (injected services) can be readonly.',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse([__DIR__ . '/Fixture/NotAComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidTwigComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidLiveComponent.php'], []);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(ForbiddenReadonlyRule::class);
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\LiveComponent\LivePropHydrationMethodsRule;

use Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LivePropHydrationMethodsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<LivePropHydrationMethodsRule>
 */
final class LivePropHydrationMethodsRuleTest extends RuleTestCase
{
    public function testMissingBothMethods(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/MissingBothMethods.php'],
            [
                [
                    'Property "data" references non-existent hydrate method "hydrateData()".',
                    13,
                    'Create the public method "hydrateData()" in the component class.',
                ],
                [
                    'Property "data" references non-existent dehydrate method "dehydrateData()".',
                    13,
                    'Create the public method "dehydrateData()" in the component class.',
                ],
            ]
        );
    }

    public function testMissingHydrateWith(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/MissingHydrateWith.php'],
            [
                [
                    'Property "data" has a #[LiveProp] attribute with "dehydrateWith" but is missing "hydrateWith".',
                    13,
                    'Both "hydrateWith" and "dehydrateWith" must be specified together in the #[LiveProp] attribute.',
                ],
            ]
        );
    }

    public function testMissingDehydrateWith(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/MissingDehydrateWith.php'],
            [
                [
                    'Property "data" has a #[LiveProp] attribute with "hydrateWith" but is missing "dehydrateWith".',
                    13,
                    'Both "hydrateWith" and "dehydrateWith" must be specified together in the #[LiveProp] attribute.',
                ],
            ]
        );
    }

    public function testPrivateHydrateMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/PrivateHydrateMethod.php'],
            [
                [
                    'Hydrate method "hydrateData()" referenced in property "data" must be public.',
                    21,
                    'Make the method "hydrateData()" public.',
                ],
            ]
        );
    }

    public function testProtectedDehydrateMethod(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/ProtectedDehydrateMethod.php'],
            [
                [
                    'Dehydrate method "dehydrateData()" referenced in property "data" must be public.',
                    21,
                    'Make the method "dehydrateData()" public.',
                ],
            ]
        );
    }

    public function testHydrateMethodReturnTypeMismatch(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/HydrateMethodReturnTypeMismatch.php'],
            [
                [
                    'Hydrate method "hydrateData()" return type must match property "data" type. Expected "array", got "string".',
                    16,
                    'Change the return type to ": array".',
                ],
            ]
        );
    }

    public function testDehydrateMethodParameterTypeMismatch(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/DehydrateMethodParameterTypeMismatch.php'],
            [
                [
                    'Dehydrate method "dehydrateData()" first parameter type must match property "data" type. Expected "array", got "string".',
                    21,
                    'Change the parameter type to "array".',
                ],
            ]
        );
    }

    public function testHydrateMethodWithoutParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/HydrateMethodWithoutParameter.php'],
            [
                [
                    'Hydrate method "hydrateData()" must have one parameter.',
                    16,
                    'Add a parameter to the hydrate method.',
                ],
            ]
        );
    }

    public function testDehydrateMethodWithoutParameter(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/DehydrateMethodWithoutParameter.php'],
            [
                [
                    'Dehydrate method "dehydrateData()" must have one parameter that matches property "data" type.',
                    21,
                    'Add a parameter to the dehydrate method that matches the property type.',
                ],
            ]
        );
    }

    public function testHydrationMethodsTypeMismatch(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/HydrationMethodsTypeMismatch.php'],
            [
                [
                    'Dehydrate method "dehydrateData()" return type must match hydrate method "hydrateData()" first parameter type. Expected "array", got "string".',
                    21,
                    'The dehydrate method should return the same type that the hydrate method accepts as its first parameter: "array".',
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
            [__DIR__ . '/Fixture/ValidHydrationMethods.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/NoHydrationMethods.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ValidTypes.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ValidComplexTypes.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(LivePropHydrationMethodsRule::class);
    }
}

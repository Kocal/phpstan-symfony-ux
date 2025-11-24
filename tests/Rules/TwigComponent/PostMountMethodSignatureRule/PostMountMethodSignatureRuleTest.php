<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\PostMountMethodSignatureRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PostMountMethodSignatureRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<PostMountMethodSignatureRule>
 */
final class PostMountMethodSignatureRuleTest extends RuleTestCase
{
    public function testInvalidVisibility(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidVisibility.php'],
            [
                [
                    'Method "postMountProtected" with #[PostMount] attribute must be public.',
                    13,
                    'Change the method visibility to public.',
                ],
                [
                    'Method "postMountPrivate" with #[PostMount] attribute must be public.',
                    18,
                    'Change the method visibility to public.',
                ],
            ]
        );
    }

    public function testInvalidParameterCount(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidParameterCount.php'],
            [
                [
                    'Method "postMountTooManyParams" with #[PostMount] attribute must have at most one parameter of type "array".',
                    13,
                    'The method should have zero or one parameter: "array $data" (optional).',
                ],
            ]
        );
    }

    public function testInvalidParameterType(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidParameterType.php'],
            [
                [
                    'Method "postMountWrongType" with #[PostMount] attribute must have a parameter of type "array".',
                    13,
                    'Change the parameter type to "array".',
                ],
                [
                    'Method "postMountIntType" with #[PostMount] attribute must have a parameter of type "array".',
                    18,
                    'Change the parameter type to "array".',
                ],
            ]
        );
    }

    public function testInvalidReturnType(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidReturnType.php'],
            [
                [
                    'Method "postMountStringReturn" with #[PostMount] attribute must have a return type of "array", "void", or "array|void".',
                    13,
                    'Change the return type to ": array", ": void", or ": array|void".',
                ],
                [
                    'Method "postMountIntReturn" with #[PostMount] attribute must have a return type of "array", "void", or "array|void".',
                    19,
                    'Change the return type to ": array", ": void", or ": array|void".',
                ],
                [
                    'Method "postMountBoolReturn" with #[PostMount] attribute must have a return type of "array", "void", or "array|void".',
                    25,
                    'Change the return type to ": array", ": void", or ": array|void".',
                ],
            ]
        );
    }

    public function testInvalidLiveComponent(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidLiveComponent.php'],
            [
                [
                    'Method "postMountWrongType" with #[PostMount] attribute must have a parameter of type "array".',
                    13,
                    'Change the parameter type to "array".',
                ],
                [
                    'Method "postMountWrongReturn" with #[PostMount] attribute must have a return type of "array", "void", or "array|void".',
                    18,
                    'Change the return type to ": array", ": void", or ": array|void".',
                ],
                [
                    'Method "postMountPrivate" with #[PostMount] attribute must be public.',
                    24,
                    'Change the method visibility to public.',
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
            [__DIR__ . '/Fixture/ValidTwigComponent.php'],
            []
        );

        $this->analyse(
            [__DIR__ . '/Fixture/ValidLiveComponent.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(PostMountMethodSignatureRule::class);
    }
}

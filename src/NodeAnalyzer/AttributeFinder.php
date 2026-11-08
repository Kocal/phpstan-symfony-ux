<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\NodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;

/**
 * Heavily inspired by https://github.com/symplify/phpstan-rules/blob/main/src/NodeAnalyzer/AttributeFinder.php <3
 */
final class AttributeFinder
{
    public static function findAttribute(ClassMethod|Property|ClassLike|Param $node, string $desiredAttributeClass): ?Attribute
    {
        $attributes = self::findAttributes($node);

        foreach ($attributes as $attribute) {
            if (! $attribute->name instanceof FullyQualified) {
                continue;
            }

            if ($attribute->name->toString() === $desiredAttributeClass) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * Find any of the provided attributes.
     *
     * @param string[] $desiredAttributeClasses
     */
    public static function findAnyAttribute(ClassMethod|Property|ClassLike|Param $node, array $desiredAttributeClasses): ?Attribute
    {
        $attributes = self::findAttributes($node);

        foreach ($attributes as $attribute) {
            if (! $attribute->name instanceof FullyQualified) {
                continue;
            }

            if (in_array($attribute->name->toString(), $desiredAttributeClasses, true)) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * @param int|null $position Zero-based position when the argument is passed positionally, null to match by name only
     */
    public static function findArgument(Attribute $attribute, string $name, ?int $position = null): ?Arg
    {
        foreach ($attribute->args as $index => $arg) {
            $isWantedArg = $arg->name === null
                ? $index === $position
                : $arg->name->toString() === $name;

            if ($isWantedArg) {
                return $arg;
            }
        }

        return null;
    }

    /**
     * Returns the value of the named argument, or null when it is missing or not a string literal.
     */
    public static function findStringArgumentValue(Attribute $attribute, string $name): ?string
    {
        $arg = self::findArgument($attribute, $name);

        return $arg?->value instanceof String_ ? $arg->value->value : null;
    }

    /**
     * @return Attribute[]
     */
    private static function findAttributes(ClassMethod|Property|ClassLike|Param $node): array
    {
        $attributes = [];

        foreach ($node->attrGroups as $attrGroup) {
            $attributes = array_merge($attributes, $attrGroup->attrs);
        }

        return $attributes;
    }
}

<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\NodeAnalyzer;

use PhpParser\Node\Attribute;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;

/**
 * Heavily inspired by https://github.com/symplify/phpstan-rules/blob/main/src/NodeAnalyzer/AttributeFinder.php <3
 */
final class AttributeFinder
{
    public static function findAttribute(ClassMethod | Property | ClassLike | Param $node, string $desiredAttributeClass): ?Attribute
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
    public static function findAnyAttribute(ClassMethod | Property | ClassLike | Param $node, array $desiredAttributeClasses): ?Attribute
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
     * @return Attribute[]
     */
    private static function findAttributes(ClassMethod | Property | ClassLike | Param $node): array
    {
        $attributes = [];

        foreach ($node->attrGroups as $attrGroup) {
            $attributes = array_merge($attributes, $attrGroup->attrs);
        }

        return $attributes;
    }
}

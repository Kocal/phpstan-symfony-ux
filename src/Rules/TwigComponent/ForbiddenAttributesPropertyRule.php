<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * @implements Rule<Class_>
 */
final class ForbiddenAttributesPropertyRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! $asTwigComponent = AttributeFinder::findAttribute($node, AsTwigComponent::class)) {
            return [];
        }

        if (! $attributesVarName = $this->getAttributesVarName($asTwigComponent)) {
            return [];
        }

        if ($propertyAttributes = $node->getProperty($attributesVarName['name'])) {
            return [
                RuleErrorBuilder::message(
                    $attributesVarName['custom']
                        ? sprintf('Using property "%s" in a Twig component is forbidden, it may lead to confusion with the "%s" attribute defined in #[AsTwigComponent].', $attributesVarName['name'], $attributesVarName['name'])
                        : sprintf('Using property "%s" in a Twig component is forbidden, it may lead to confusion with the default "attributes" Twig variable.', $attributesVarName['name'])
                )
                    ->identifier('SymfonyUX.TwigComponent.forbiddenAttributesProperty')
                    ->line($propertyAttributes->getLine())
                    ->tip('Consider renaming or removing this property to avoid conflicts with the Twig component attributes.')
                    ->build(),

            ];
        }

        return [];
    }

    /**
     * @return {name: string, custom: false}|null
     */
    private function getAttributesVarName(Node\Attribute $attribute): ?array
    {
        foreach ($attribute->args as $arg) {
            if ($arg->name && $arg->name->toString() === 'attributesVar') {
                if ($arg->value instanceof Node\Scalar\String_) {
                    return [
                        'name' => $arg->value->value,
                        'custom' => true,
                    ];
                }
            }
        }

        $reflAttribute = new \ReflectionClass(AsTwigComponent::class);
        foreach ($reflAttribute->getConstructor()->getParameters() as $reflParameter) {
            if ($reflParameter->getName() === 'attributesVar' && $reflParameter->isDefaultValueAvailable()) {
                return [
                    'name' => $reflParameter->getDefaultValue(),
                    'custom' => false,
                ];
            }
        }

        return null;
    }
}

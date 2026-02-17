<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Rules\TwigComponent;

use Kocal\PHPStanSymfonyUX\NodeAnalyzer\AttributeFinder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * @implements Rule<Class_>
 */
final class ForbiddenReadonlyRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
            return [];
        }

        $errors = [];

        // Check if class is readonly
        if ($node->isReadonly()) {
            $errors[] = RuleErrorBuilder::message('Twig component class must not be readonly.')
                ->identifier('symfonyUX.twigComponent.forbiddenReadonlyClass')
                ->line($node->getLine())
                ->tip('Remove the "readonly" keyword from the class declaration. Twig components need mutable properties to receive props.')
                ->build();
        }

        // Get constructor parameter names to exclude them from validation
        $constructorParamNames = $this->getConstructorParameterNames($node);

        // Check for readonly properties (excluding constructor-promoted parameters)
        foreach ($node->getProperties() as $property) {
            if (! $property->isReadonly()) {
                continue;
            }

            foreach ($property->props as $prop) {
                $propertyName = $prop->name->toString();

                // Skip if this property is a constructor parameter (injected service)
                if (\in_array($propertyName, $constructorParamNames, true)) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message(sprintf('Property "$%s" must not be readonly.', $propertyName))
                    ->identifier('symfonyUX.twigComponent.forbiddenReadonlyProperty')
                    ->line($property->getLine())
                    ->tip('Remove the "readonly" keyword from the property declaration. Only constructor-promoted properties (injected services) can be readonly.')
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * @return list<string>
     */
    private function getConstructorParameterNames(Class_ $node): array
    {
        $constructor = $node->getMethod('__construct');
        if ($constructor === null) {
            return [];
        }

        $paramNames = [];
        foreach ($constructor->params as $param) {
            if ($param->var instanceof Node\Expr\Variable && \is_string($param->var->name)) {
                $paramNames[] = $param->var->name;
            }
        }

        return $paramNames;
    }
}

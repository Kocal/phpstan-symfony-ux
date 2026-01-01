# AI Agent Instructions - PHPStan Symfony UX

Reference guide for AI agents working on this PHPStan rules project for Symfony UX.

## Project Overview

This project provides custom PHPStan rules to improve static analysis of Symfony UX applications, specifically for:

- **TwigComponent**: Twig components (classes annotated with `#[AsTwigComponent]`)
- **LiveComponent**: Live components (classes annotated with `#[AsLiveComponent]`)

> [!IMPORTANT]
> TwigComponent rules also apply to LiveComponents since a LiveComponent is an extension of a TwigComponent. Use `AttributeFinder::findAnyAttribute()` to target both attributes.

## Project Structure

```
src/
├── NodeAnalyzer/
│   └── AttributeFinder.php          # Utility to find PHP attributes
└── Rules/
    ├── TwigComponent/                # Rules for TwigComponent (also apply to LiveComponents)
    │   ├── ClassMustBeFinalRule.php
    │   ├── ForbiddenAttributesPropertyRule.php
    │   └── ...
    └── LiveComponent/                # LiveComponent-specific rules
        ├── LivePropHydrationMethodsRule.php
        └── ...

tests/
└── Rules/
    ├── TwigComponent/
    │   └── ClassMustBeFinalRule/
    │       ├── ClassMustBeFinalRuleTest.php
    │       ├── config/
    │       │   └── configured_rule.neon
    │       └── Fixture/
    │           ├── InvalidNonFinalTwigComponent.php
    │           ├── ValidTwigComponent.php
    │           └── NotAComponent.php
    └── LiveComponent/
        └── ...
```

## Available Commands

| Command | Description |
|---------|-------------|
| `symfony composer qa-fix` | Runs cs-fix + phpstan + tests (run before each commit) |
| `symfony composer phpstan` | PHPStan analysis of the project |
| `symfony composer test` | Runs PHPUnit tests |
| `symfony composer cs` | Checks code style |
| `symfony composer cs-fix` | Automatically fixes code style |

## Creating a New Rule

### Step 1: Create the Rule Class

Location: `src/Rules/<Package>/<RuleName>Rule.php`

```php
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
final class ExampleRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        // 1. Check for attribute presence (early return)
        if (! AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
            return [];
        }

        // 2. Validation logic
        if ($violationDetected) {
            return [
                RuleErrorBuilder::message('Clear description of the problem.')
                    ->identifier('symfonyUX.twigComponent.exampleRule')
                    ->line($node->getLine())
                    ->tip('Suggestion to fix the problem.')
                    ->build(),
            ];
        }

        return [];
    }
}
```

### Step 2: Create Tests

Required structure:
```
tests/Rules/TwigComponent/ExampleRule/
├── ExampleRuleTest.php
├── config/
│   └── configured_rule.neon
└── Fixture/
    ├── InvalidTwigComponent.php
    ├── InvalidLiveComponent.php
    ├── ValidTwigComponent.php
    ├── ValidLiveComponent.php
    └── NotAComponent.php
```

#### Test File (`ExampleRuleTest.php`)

```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExampleRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExampleRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ExampleRule>
 */
final class ExampleRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidTwigComponent.php'],
            [
                [
                    'Expected error message.',
                    10, // Line number
                    'Expected tip.',
                ],
            ]
        );

        $this->analyse(
            [__DIR__ . '/Fixture/InvalidLiveComponent.php'],
            [
                [
                    'Expected error message.',
                    10,
                    'Expected tip.',
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
        return self::getContainer()->getByType(ExampleRule::class);
    }
}
```

#### Configuration (`config/configured_rule.neon`)

```yaml
includes:
    - ../../../../test-extension.neon

rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExampleRule
```

#### Fixtures

Fixtures must cover both TwigComponent AND LiveComponent cases:

**`InvalidTwigComponent.php`**:
```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExampleRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class InvalidTwigComponent  // Example: non-final class
{
}
```

**`InvalidLiveComponent.php`**:
```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExampleRule\Fixture;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent]
class InvalidLiveComponent
{
}
```

**`ValidTwigComponent.php`**:
```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExampleRule\Fixture;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ValidTwigComponent
{
}
```

**`NotAComponent.php`**:
```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\ExampleRule\Fixture;

final class NotAComponent  // No attribute = no error
{
}
```

### Step 3: Document in README.md

Add under the appropriate section (`## TwigComponent Rules` or `## LiveComponent Rules`):

```markdown
### ExampleRule

Description of what the rule checks and why it's important.

\`\`\`yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExampleRule
\`\`\`

\`\`\`php
#[AsTwigComponent]
class BadExample  // Invalid example
{
}
\`\`\`

:x:

<br>

\`\`\`php
#[AsTwigComponent]
final class GoodExample  // Valid example
{
}
\`\`\`

:+1:

<br>
```

## Code Conventions

### Variable Naming

| Type | Convention | Example |
|------|------------|---------|
| Class reflection | `$reflClass` | `$reflClass = $this->reflectionProvider->getClass(...)` |
| Method reflection | `$reflMethod` or `$<name>MethodRefl` | `$hydrateMethodRefl` |
| PHP attribute | `$attribute` or `$<name>Attribute` | `$livePropAttribute` |
| AST node | Descriptive name | `$method`, `$property`, `$node` |

### Rule Structure

1. **Declaration**: `declare(strict_types=1);`
2. **PHPDoc**: `@implements Rule<Class_>` on the class
3. **Class**: `final class` with name ending in `Rule`
4. **Constructor**: Inject `ReflectionProvider` if needed
5. **Methods** in this order:
   - `getNodeType()`
   - `processNode()`
   - Private methods (alphabetical order)

### Validation Order in `processNode()`

```php
public function processNode(Node $node, Scope $scope): array
{
    // 1. Check for required attribute
    if (! AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
        return [];
    }

    // 2. Check namespacedName if using reflection
    if ($node->namespacedName === null) {
        return [];
    }

    // 3. Initialize errors array
    $errors = [];

    // 4. Get reflection if needed
    $reflClass = $this->reflectionProvider->getClass($node->namespacedName->toString());

    // 5. Iterate and validate
    foreach ($node->getMethods() as $method) {
        // validation...
        $errors[] = RuleErrorBuilder::message('...')
            ->identifier('symfonyUX.twigComponent.ruleName')
            ->line($method->getLine())
            ->tip('...')
            ->build();
    }

    // 6. Return errors
    return $errors;
}
```

### Error Messages

- **Modal verb**: Use "must" (obligation), not "should"
- **Format**: `sprintf()` with `%s` for variables
- **Tip**: Always include an actionable suggestion
- **Identifier**: Format `symfonyUX.<package>.<ruleName>` (camelCase)

Examples:
```php
// Correct
RuleErrorBuilder::message('Twig component class must be final.')
    ->identifier('symfonyUX.twigComponent.classMustBeFinal')
    ->tip('Add the "final" keyword to the class declaration.')

RuleErrorBuilder::message(sprintf('Method "%s()" must be public.', $methodName))
    ->identifier('symfonyUX.liveComponent.methodMustBePublic')
    ->tip(sprintf('Change the visibility of "%s()" to public.', $methodName))
```

## Using AttributeFinder

`AttributeFinder` is a static utility for searching PHP attributes on AST nodes.

### Available Methods

```php
// Find a specific attribute
$attribute = AttributeFinder::findAttribute($node, AsLiveComponent::class);

// Find any of the attributes (TwigComponent OR LiveComponent)
$attribute = AttributeFinder::findAnyAttribute($node, [
    AsTwigComponent::class,
    AsLiveComponent::class,
]);
```

### Use Cases

**TwigComponent rule (also applies to LiveComponents)**:
```php
if (! AttributeFinder::findAnyAttribute($node, [AsTwigComponent::class, AsLiveComponent::class])) {
    return [];
}
```

**LiveComponent-only rule**:
```php
if (! AttributeFinder::findAttribute($node, AsLiveComponent::class)) {
    return [];
}
```

## Existing Rules as References

### Simple Rules (without ReflectionProvider)

- `ClassMustBeFinalRule`: Checks that the class is `final`
- `ClassNameMustNotEndWithComponentRule`: Checks class name
- `ForbiddenClassPropertyRule`: Forbids a property named `$class`

### Rules with ReflectionProvider

- `ForbiddenAttributesPropertyRule`: Uses reflection to read attribute parameters
- `MethodsVisibilityRule`: Analyzes methods via trait reflection
- `LivePropHydrationMethodsRule`: Complex type validation with reflection

## Development Workflow

```bash
# 1. Create the rule and tests

# 2. Verify everything works
symfony composer qa-fix

# 3. If style errors occur, they are automatically fixed
#    If tests fail, fix and rerun

# 4. Document in README.md

# 5. Commit
```

## Common Mistakes to Avoid

1. **Forgetting to test with LiveComponent**: Fixtures must include cases for `#[AsLiveComponent]`
2. **Forgetting `->tip()`**: Every error must have an actionable tip
3. **Wrong identifier**: Use the format `symfonyUX.<package>.<ruleName>`
4. **Forgetting `->line()`**: Always indicate the relevant line
5. **Not checking `namespacedName`**: Required before using `reflectionProvider->getClass()`
6. **Incorrect neon file**: Don't forget `includes: - ../../../../test-extension.neon`

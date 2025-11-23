# AI Agent Instructions - PHPStan Symfony UX Project

## Project Overview

This project contains custom PHPStan rules to improve static analysis of Symfony UX applications, particularly for Twig components.

## Project Structure

- `src/Rules/<UX Package>/` : Contains PHPStan rules for a given UX package (e.g.: `src/Rules/TwigComponent/`)
- `src/NodeAnalyzer/` : Contains reusable analyzers (e.g., `AttributeFinder`)
- `tests/Rules/<UX Package>/` : Contains tests for each rule for a given UX package (e.g.: `tests/Rules/TwigComponent/`)
- `README.md` : Documentation of available rules

## How to Create a New PHPStan Rule

The code examples below are mainly written for TwigComponent, but it must be adapted:
- the rules are organized by UX Packages
- some code or files are maybe not necessary for other UX Packages

### 1. Create the rule class in `src/Rules/<UX Package>/`

Each rule must:
- Implement PHPStan's `Rule` interface
- Return an array of errors via `RuleErrorBuilder`

Typical structure:
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
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * @implements Rule<Class_>
 */
final class MyRuleRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (! AttributeFinder::findAttribute($node, AsTwigComponent::class)) {
            return [];
        }

        // Validation logic here

        if ($errorCondition) {
            return [
                RuleErrorBuilder::message('Clear and descriptive error message.')
                    ->identifier('symfonyUX.twigComponent.uniqueIdentifier')
                    ->line($node->getLine())
                    ->tip('Suggestion to fix the issue.')
                    ->build(),
            ];
        }

        return [];
    }
}
```

### 2. Create tests in `tests/Rules/<UX Package>/`

Required structure:
```
tests/Rules/TwigComponent/MyRuleRule/
├── MyRuleRuleTest.php
├── Fixture/
│   ├── InvalidCase.php (case that should fail)
│   ├── ValidCase.php (case that should pass)
│   └── NotAComponent.php (class without AsTwigComponent attribute)
└── config/
    └── configured_rule.neon
```

#### Main test file (`MyRuleRuleTest.php`):
```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MyRuleRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MyRuleRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<MyRuleRule>
 */
final class MyRuleRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidCase.php'],
            [
                [
                    'Expected error message.',
                    10, // Line number
                    'Expected suggestion.',
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
            [__DIR__ . '/Fixture/ValidCase.php'],
            []
        );
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(MyRuleRule::class);
    }
}
```

#### Configuration (`config/configured_rule.neon`):
```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MyRuleRule
```

#### Fixtures:
- **InvalidCase.php**: Example that violates the rule
- **ValidCase.php**: Example that complies with the rule
- **NotAComponent.php**: Class without `#[AsTwigComponent]` (should not trigger an error)

### 3. Document the rule in `README.md`

Add a new section under `## TwigComponent Rules`:
```markdown
### MyRuleRule

Clear description of what the rule checks and why.

\`\`\`yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MyRuleRule
\`\`\`

\`\`\`php
// Invalid code example
#[AsTwigComponent]
final class BadExample
{
}
\`\`\`

:x:

<br>

\`\`\`php
// Valid code example
#[AsTwigComponent]
final class GoodExample
{
}
\`\`\`

:+1:

<br>
```

## Useful Commands

### Check and fix syntax + run tests
```bash
symfony composer qa-fix
```

This command will:
- Verify that changes are syntactically valid
- Automatically fix code style issues
- Run all tests to ensure they pass

### Other available commands
Check the `composer.json` file to see all available commands.

## Best Practices

1. **Naming**: Rules should have a descriptive name and end with `Rule`
2. **Identifiers**: Use the format `symfonyUX.twigComponent.descriptiveName` for error identifiers
3. **Clear messages**: Error messages should be explicit and include a `tip()` with a suggestion
4. **Complete tests**: Always test valid cases, invalid cases, and non-components
5. **Documentation**: Document each rule in the README with concrete examples
6. **Validation**: Always run `symfony composer qa-fix` before committing

## Examples of Existing Rules

- `ForbiddenAttributesPropertyRule`: Forbids the `$attributes` property
- `ForbiddenClassPropertyRule`: Forbids the `$class` property
- `ClassNameShouldNotEndWithComponentRule`: Class names should not end with "Component"

These rules can serve as references for implementing new rules.

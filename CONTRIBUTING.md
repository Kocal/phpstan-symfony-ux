# Contributing to PHPStan Symfony UX

Thank you for your interest in contributing to PHPStan Symfony UX! This document provides guidelines and instructions for contributing to the project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Creating a New Rule](#creating-a-new-rule)
- [Testing](#testing)
- [Code Quality](#code-quality)
- [Submitting a Pull Request](#submitting-a-pull-request)
- [Project Structure](#project-structure)

## Code of Conduct

See [CODE_OF_CONDUCT.md](./CODE_OF_CONDUCT.md) for details on our code of conduct and how to report issues.

## Getting Started

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/phpstan-symfony-ux.git
   cd phpstan-symfony-ux
   ```
3. **Install dependencies**:
   ```bash
   composer install
   ```

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer

### Available Commands

The project provides several Composer scripts to help with development:

```bash
# Run all quality checks and tests
composer qa-fix

# Run PHPStan analysis
composer phpstan

# Run tests
composer test

# Check code style
composer cs

# Fix code style automatically
composer cs-fix
```

## Creating a New Rule

### 1. Create the Rule Class

Create your rule in the appropriate directory under `src/Rules/`:
- `src/Rules/TwigComponent/` for TwigComponent rules
- `src/Rules/LiveComponent/` for LiveComponent rules

Each rule must:
- Implement PHPStan's `Rule` interface
- Return errors via `RuleErrorBuilder`
- Have a descriptive name ending with `Rule`

**Example structure:**

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
final class MyNewRule implements Rule
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

        // Validation logic here

        if ($errorCondition) {
            return [
                RuleErrorBuilder::message('Clear and descriptive error message.')
                    ->identifier('symfonyUX.twigComponent.descriptiveName')
                    ->line($node->getLine())
                    ->tip('Suggestion to fix the issue.')
                    ->build(),
            ];
        }

        return [];
    }
}
```

### 2. Create Tests

Create a test directory structure:

```
tests/Rules/TwigComponent/MyNewRule/
├── MyNewRuleTest.php
├── Fixture/
│   ├── InvalidCase.php
│   ├── ValidCase.php
│   └── NotAComponent.php
└── config/
    └── configured_rule.neon
```

**Test class example:**

```php
<?php

declare(strict_types=1);

namespace Kocal\PHPStanSymfonyUX\Tests\Rules\TwigComponent\MyNewRule;

use Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MyNewRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<MyNewRule>
 */
final class MyNewRuleTest extends RuleTestCase
{
    public function testViolations(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixture/InvalidCase.php'],
            [
                [
                    'Expected error message.',
                    10, // Line number
                    'Expected tip.',
                ],
            ]
        );
    }

    public function testNoViolations(): void
    {
        $this->analyse([__DIR__ . '/Fixture/NotAComponent.php'], []);
        $this->analyse([__DIR__ . '/Fixture/ValidCase.php'], []);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(MyNewRule::class);
    }
}
```

**Fixtures:**
- `InvalidCase.php`: Code that violates the rule
- `ValidCase.php`: Code that complies with the rule
- `NotAComponent.php`: Code without component attributes (should not trigger errors)

### 3. Document the Rule

Add documentation to `README.md` under the appropriate section (TwigComponent or LiveComponent):

```markdown
### MyNewRule

Description of what the rule checks and why it's important.

\`\`\`yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MyNewRule
\`\`\`

\`\`\`php
// Invalid example
#[AsTwigComponent]
final class BadExample
{
    // ...
}
\`\`\`

:x:

<br>

\`\`\`php
// Valid example
#[AsTwigComponent]
final class GoodExample
{
    // ...
}
\`\`\`

:+1:

<br>
```

## Testing

Always write comprehensive tests for your rules:

```bash
# Run all tests
composer test

# Run specific test
vendor/bin/phpunit tests/Rules/TwigComponent/MyNewRule/
```

Test cases should include:
- Invalid code that violates the rule
- Valid code that complies with the rule
- Edge cases
- Classes without component attributes

## Code Quality

Before submitting a PR, ensure your code passes all quality checks:

```bash
# Run all checks and auto-fix issues
composer qa-fix
```

This will:
1. Fix code style issues automatically
2. Run PHPStan analysis
3. Run all tests

### Code Style

- Use strict types: `declare(strict_types=1);`
- Follow PSR-12 coding standards
- Use type declarations for all properties, parameters, and return types
- Make classes final when possible
- Use descriptive variable and method names

### Error Messages

- Use clear, descriptive error messages
- Always include a `tip()` with actionable advice
- Use proper error identifiers: `symfonyUX.{package}.{descriptiveName}`

## Submitting a Pull Request

### Before Submitting

1. **Run quality checks**: `composer qa-fix`
2. **Update documentation**: Add/update relevant sections in README.md
3. **Write tests**: Ensure comprehensive test coverage
4. **Commit with clear messages**: Use descriptive commit messages

### PR Guidelines

1. **Create a focused PR**: One feature or fix per PR
2. **Write a clear description**:
   - What problem does it solve?
   - How does it solve it?
   - Are there any breaking changes?
3. **Reference related issues**: Use "Fixes #123" or "Closes #123"
4. **Update the CHANGELOG**: If applicable, add a note about your change

### PR Checklist

- [ ] Code follows project style guidelines
- [ ] All tests pass (`composer test`)
- [ ] PHPStan analysis passes (`composer phpstan`)
- [ ] Code style is correct (`composer cs`)
- [ ] New rules are documented in README.md
- [ ] Tests are comprehensive and cover edge cases
- [ ] Commit messages are clear and descriptive

## Project Structure

```
phpstan-symfony-ux/
├── src/
│   ├── NodeAnalyzer/        # Reusable analyzers
│   └── Rules/               # PHPStan rules
│       ├── LiveComponent/   # LiveComponent-specific rules
│       └── TwigComponent/   # TwigComponent rules
├── tests/
│   └── Rules/               # Tests organized by rule
├── composer.json            # Dependencies and scripts
├── extension.neon           # PHPStan extension configuration
├── phpstan.dist.neon        # PHPStan configuration
├── ecs.php                  # Easy Coding Standard configuration
└── README.md                # User documentation
```

## Best Practices

1. **Naming**: Rules should have descriptive names ending with `Rule`
2. **Identifiers**: Use the format `symfonyUX.{package}.{descriptiveName}`
3. **Error messages**: Be clear, concise, and include actionable tips
4. **Tests**: Cover all scenarios (valid, invalid, edge cases, non-components)
5. **Documentation**: Provide clear examples in README.md
6. **Code review**: Be open to feedback and iterate on your PR

## Questions or Issues?

If you have questions or run into issues:

1. Check existing [GitHub Issues](https://github.com/Kocal/phpstan-symfony-ux/issues)
2. Review the [AGENTS.md](./AGENTS.md) file for AI agent instructions
3. Open a new issue with a clear description

## License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to PHPStan Symfony UX! 🎉

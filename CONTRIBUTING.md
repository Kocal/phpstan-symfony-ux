# Contributing to PHPStan Symfony UX

Thank you for your interest in contributing to PHPStan Symfony UX!

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

| Command | Description |
|---------|-------------|
| `symfony composer qa-fix` | Runs cs-fix + phpstan + tests (run before each commit) |
| `symfony composer phpstan` | PHPStan analysis of the project |
| `symfony composer test` | Runs PHPUnit tests |
| `symfony composer cs` | Checks code style |
| `symfony composer cs-fix` | Automatically fixes code style |

## Creating Rules, Testing & Code Conventions

For detailed instructions on:
- Creating new PHPStan rules
- Writing tests and fixtures
- Code conventions and best practices
- Project structure

Please refer to [AGENTS.md](./AGENTS.md).

## Questions or Issues?

If you have questions or run into issues:

1. Check existing [GitHub Issues](https://github.com/Kocal/phpstan-symfony-ux/issues)
2. Review the [AGENTS.md](./AGENTS.md) file for detailed development instructions
3. Open a new issue with a clear description

## License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to PHPStan Symfony UX! 🎉

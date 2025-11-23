# PHPStan for Symfony UX

A set of PHPStan rules to improve static analysis for Symfony UX applications.

## Installation

To install the PHPStan rules for Symfony UX, you can use Composer:

```bash
composer require --dev kocal/phpstan-symfony-ux
```

## TwigComponent Rules

### ForbiddenAttributesPropertyRule

Forbid the use of the `$attributes` property in Twig Components, which can lead to confusion when using `{{ attributes }}` (an instance of `ComponentAttributes` that is automatically injected) in Twig templates.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenAttributesPropertyRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public $attributes;
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(attributesVar: 'customAttributes')]
final class Alert
{
    public $customAttributes;
}
```

:x:

<br>

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public $customAttributes;
}
```

:+1:

<br>

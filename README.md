# PHPStan for Symfony UX

A set of PHPStan rules to improve static analysis for [Symfony UX](https://github.com/symfony/ux) applications.

## Installation

To install the PHPStan rules for Symfony UX, you can use Composer:

```bash
composer require --dev kocal/phpstan-symfony-ux
```

## Configuration

After installing the package, you need to configure PHPStan to use the rules.

Each rule can be enabled individually by adding it to your `phpstan.dist.neon` configuration file.

## TwigComponent Rules

> [!NOTE]
> All these rules also apply to LiveComponents (classes annotated with `#[AsLiveComponent]`).

### ClassNameShouldNotEndWithComponentRule

Forbid Twig Component class names from ending with "Component" suffix, as it creates redundancy since the class is already identified as a component through the `#[AsTwigComponent]` attribute.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassNameShouldNotEndWithComponentRule
```

```php
// src/Twig/Components/AlertComponent.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class AlertComponent
{
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

:+1:

<br>

### ExposePublicPropsShouldBeFalseRule

Enforces that the `#[AsTwigComponent]` attribute has its `exposePublicProps` parameter explicitly set to `false`.
This prevents public properties from being automatically exposed to templates, promoting explicit control over what data is accessible in your Twig components.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ExposePublicPropsShouldBeFalseRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public string $message;
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(exposePublicProps: true)]
final class Alert
{
    public string $message;
}
```

:x:

<br>

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(exposePublicProps: false)]
final class Alert
{
    public string $message;
}
```

:+1:

<br>

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

### ForbiddenClassPropertyRule

Forbid the use of the `$class` property in Twig Components, as it is considered a bad practice to manipulate CSS classes directly in components.
Use `{{ attributes }}` or `{{ attributes.defaults({ class: '...' }) }}` in your Twig templates instead.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenClassPropertyRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public $class;
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

:+1:

<br>

### ForbiddenInheritanceRule

Forbids the use of class inheritance in Twig Components. Composition via traits should be used instead.
This promotes better code reusability and avoids tight coupling between components.

> [!TIP]
> Another alternative is to use [Class Variant Authority](https://symfony.com/bundles/ux-twig-component/current/index.html#component-with-complex-variants-cva) to create variations of a base component without inheritance or traits,
> for example `<twig:Alert variant="success"></twig:Alert>` instead of `<twig:AlertSuccess></twig:AlertSuccess>`.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ForbiddenInheritanceRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

abstract class BaseComponent
{
    public string $name;
}

#[AsTwigComponent]
final class Alert extends BaseComponent
{
}
```

:x:

<br>

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

trait CommonComponentTrait
{
    public string $name;
}

#[AsTwigComponent]
final class Alert
{
    use CommonComponentTrait;
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public string $name;
}
```

:+1:

<br>

### PublicPropertiesShouldBeCamelCaseRule

Enforces that all public properties in Twig Components follow camelCase naming convention.
This ensures consistency and better integration with Twig templates where properties are passed and accessed using camelCase.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PublicPropertiesShouldBeCamelCaseRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public string $user_name;
    public bool $is_active;
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public string $UserName;
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
    public string $userName;
    public bool $isActive;
}
```

:+1:

<br>

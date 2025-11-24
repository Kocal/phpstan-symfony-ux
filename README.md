# PHPStan for Symfony UX

A set of PHPStan rules to improve static analysis for [Symfony UX](https://github.com/symfony/ux) applications.

## Installation

To install the PHPStan rules for Symfony UX, you can use Composer:

```bash
composer require --dev kocal/phpstan-symfony-ux
```

If you have [phpstan/extension-installer](https://github.com/phpstan/extension-installer) installed (which is the case by default), the extension will be automatically registered and you're ready to go.

If you don't use the extension installer, you'll need to manually add the extension to your `phpstan.neon` or `phpstan.dist.neon` configuration file:

```yaml
includes:
    - vendor/kocal/phpstan-symfony-ux/extension.neon
```

## Configuration

Each rule can be enabled individually by adding it to your `phpstan.neon` or `phpstan.dist.neon` configuration file.

## LiveComponent Rules

### LiveActionMethodsShouldBePublicRule

Enforces that all methods annotated with `#[LiveAction]` in LiveComponents must be declared as public.
LiveAction methods need to be publicly accessible to be invoked as component actions from the frontend.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveActionMethodsShouldBePublicRule
```

```php
// src/Twig/Components/TodoList.php
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class TodoList
{
    #[LiveAction]
    private function addItem(): void
    {
    }
}
```

```php
// src/Twig/Components/TodoList.php
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class TodoList
{
    #[LiveAction]
    protected function deleteItem(): void
    {
    }
}
```

:x:

<br>

```php
// src/Twig/Components/TodoList.php
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class TodoList
{
    #[LiveAction]
    public function addItem(): void
    {
    }

    #[LiveAction]
    public function deleteItem(): void
    {
    }
}
```

:+1:

<br>

### LiveListenerMethodsShouldBePublicRule

Enforces that all methods annotated with `#[LiveListener]` in LiveComponents must be declared as public.
LiveListener methods need to be publicly accessible to be invoked when listening to events from the frontend.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\LiveComponent\LiveListenerMethodsShouldBePublicRule
```

```php
// src/Twig/Components/Notification.php
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

#[AsLiveComponent]
final class Notification
{
    #[LiveListener('notification:received')]
    private function onNotificationReceived(): void
    {
    }

    #[LiveListener('notification:dismissed')]
    protected function onNotificationDismissed(): void
    {
    }
}
```

:x:

<br>

```php
// src/Twig/Components/Notification.php
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveListener;

#[AsLiveComponent]
final class Notification
{
    #[LiveListener('notification:received')]
    public function onNotificationReceived(): void
    {
    }

    #[LiveListener('notification:dismissed')]
    public function onNotificationDismissed(): void
    {
    }
}
```

:+1:

<br>

## TwigComponent Rules

> [!NOTE]
> All these rules also apply to LiveComponents (classes annotated with `#[AsLiveComponent]`).

### ClassMustBeFinalRule

Enforces that all Twig Component classes must be declared as `final`.
This prevents inheritance and promotes composition via traits, ensuring better code maintainability and avoiding tight coupling between components.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\ClassMustBeFinalRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Alert
{
    public string $message;
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
abstract class Alert
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

#[AsTwigComponent]
final class Alert
{
    public string $message;
}
```

:+1:

<br>

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

### MethodsShouldBePublicOrPrivateRule

Enforces that all methods in Twig Components are either public or private, but not protected.
Since Twig Components must be final classes and inheritance is forbidden (see `ForbiddenInheritanceRule`), protected methods serve no purpose and should be avoided.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\MethodsShouldBePublicOrPrivateRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class Alert
{
    public string $message;

    protected function formatMessage(): string
    {
        return strtoupper($this->message);
    }
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
    public string $message;

    public function formatMessage(): string
    {
        return strtoupper($this->message);
    }

    private function helperMethod(): void
    {
        // ...
    }
}
```

:+1:

<br>

### PostMountMethodSignatureRule

Enforces that methods with the `#[PostMount]` attribute have the correct signature: they must be public with an optional parameter of type `array`, and a return type of `array`, `void`, or `array|void`.
This ensures proper integration with the Symfony UX TwigComponent lifecycle hooks.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PostMountMethodSignatureRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class Alert
{
    #[PostMount]
    protected function postMount(): void
    {
    }
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class Alert
{
    #[PostMount]
    public function postMount(array $data): string
    {
        return 'invalid';
    }
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class Alert
{
    #[PostMount]
    public function postMount(string $data): void
    {
    }
}
```

:x:

<br>

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class Alert
{
    #[PostMount]
    public function postMount(): void
    {
    }
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent]
final class Alert
{
    #[PostMount]
    public function postMount(array $data): array
    {
        return $data;
    }
}
```

:+1:

<br>

### PreMountMethodSignatureRule

Enforces that methods with the `#[PreMount]` attribute have the correct signature: they must be public and have exactly one parameter of type `array`, with a return type of `array`, `void`, or `array|void` .
This ensures proper integration with the Symfony UX TwigComponent lifecycle hooks.

```yaml
rules:
    - Kocal\PHPStanSymfonyUX\Rules\TwigComponent\PreMountMethodSignatureRule
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
final class Alert
{
    #[PreMount]
    protected function preMount(array $data): array
    {
        return $data;
    }
}
```

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
final class Alert
{
    #[PreMount]
    public function preMount(string $data): array
    {
        return [];
    }
}
```

:x:

<br>

```php
// src/Twig/Components/Alert.php
namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
final class Alert
{
    #[PreMount]
    public function preMount(array $data): array
    {
        $data['timestamp'] = time();

        return $data;
    }
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

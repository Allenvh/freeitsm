# landingInstallDefault()

The install-wide default, or 'analyst' when unset — the historical behaviour.

```php
function landingInstallDefault(PDO $conn): string
```

Defined in [includes/landing.php](/code/files/includes/landing.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 | `landingInstallDefault` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[landingIsValid()](/code/functions/landingIsValid.md)

## Called from

- [api/system/get_branding.php](/code/files/api/system/get_branding.php.md)
- [includes/landing.php](/code/files/includes/landing.php.md) - in [landingResolve()](/code/functions/landingResolve.md)
- [system/preferences/index.php](/code/files/system/preferences/index.php.md)


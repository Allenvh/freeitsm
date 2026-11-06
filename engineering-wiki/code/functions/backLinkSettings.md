# backLinkSettings()

The three settings, raw - for System -> Branding.

```php
function backLinkSettings(PDO $conn): array
```

Defined in [includes/back_link.php](/code/files/includes/back_link.php.md), line 28.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 | `backLinkSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/get_branding.php](/code/files/api/system/get_branding.php.md)
- [includes/back_link.php](/code/files/includes/back_link.php.md) - in [backLinkHtml()](/code/functions/backLinkHtml.md)


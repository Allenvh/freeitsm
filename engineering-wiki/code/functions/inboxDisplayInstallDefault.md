# inboxDisplayInstallDefault()

The install-wide default an administrator has set, or the shipped one.

```php
function inboxDisplayInstallDefault(PDO $conn): array
```

Defined in [includes/inbox_display.php](/code/files/includes/inbox_display.php.md), line 132.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `inboxDisplayInstallDefault` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[inboxDisplayDefaults()](/code/functions/inboxDisplayDefaults.md), [inboxDisplayNormalise()](/code/functions/inboxDisplayNormalise.md)

## Called from

- [api/tickets/save_inbox_display.php](/code/files/api/tickets/save_inbox_display.php.md)
- [includes/inbox_display.php](/code/files/includes/inbox_display.php.md) - in [inboxDisplayForAnalyst()](/code/functions/inboxDisplayForAnalyst.md)


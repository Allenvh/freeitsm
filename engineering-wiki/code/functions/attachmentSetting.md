# attachmentSetting()

One setting, cached per request.

```php
function attachmentSetting(?PDO $conn, string $key, string $default): string
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings key to read |
| `$default` | `string` |  | returned when the row is missing, empty or unreadable; the first answer per key is cached |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `attachmentSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md), [oversizeRules()](/code/functions/oversizeRules.md)


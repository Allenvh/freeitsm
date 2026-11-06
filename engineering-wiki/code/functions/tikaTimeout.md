# tikaTimeout()

How many seconds to wait for Tika, from the override or the system setting, kept within the allowed range.

```php
function tikaTimeout(PDO $conn): int
```

Defined in [includes/search/tika.php](/code/files/includes/search/tika.php.md), line 88.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 93 | `tikaTimeout` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[tikaTimeoutOverride()](/code/functions/tikaTimeoutOverride.md)

## Called from

- [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md)
- [includes/search/tika.php](/code/files/includes/search/tika.php.md) - in [tikaExtract()](/code/functions/tikaExtract.md)


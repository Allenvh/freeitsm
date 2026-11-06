# extractQueueSettingOn()

Read a boolean setting that defaults to ON when absent.

```php
function extractQueueSettingOn(PDO $conn, string $key): bool
```

Defined in [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings key to read, e.g. EXTRACT_SETTING_CRON; absent or blank counts as on |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `extractQueueSettingOn` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [cron/attachment_extract.php](/code/files/cron/attachment_extract.php.md)
- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md)


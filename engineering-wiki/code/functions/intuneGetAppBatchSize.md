# intuneGetAppBatchSize()

Read the configured app sync batch size from system_settings, with a sane default and bounds (1..500).

```php
function intuneGetAppBatchSize(PDO $conn): int
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 611.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 612 | `intuneGetAppBatchSize` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneCreateAppSyncJob()](/code/functions/intuneCreateAppSyncJob.md)


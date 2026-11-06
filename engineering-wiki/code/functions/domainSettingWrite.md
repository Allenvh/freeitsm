# domainSettingWrite()

Write one setting (no validation — callers go through `domainSettingValidate()`).

```php
function domainSettingWrite(PDO $conn, string $key, string $value): void
```

Defined in [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), line 177.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings key to write |
| `$value` | `string` |  | the value to store, already validated |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 180 | `domainSettingWrite` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainOpportunisticRun()](/code/functions/domainOpportunisticRun.md)


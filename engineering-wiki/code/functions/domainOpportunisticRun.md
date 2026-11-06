# domainOpportunisticRun()

The page-load run. Throttled to once an hour, short, and silent: it runs inside a request somebody may be waiting on.

```php
function domainOpportunisticRun(PDO $conn): ?array
```

Defined in [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), line 160.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `domainOpportunisticRun` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[domainScheduledRun()](/code/functions/domainScheduledRun.md), [domainSetting()](/code/functions/domainSetting.md), [domainSettingWrite()](/code/functions/domainSettingWrite.md)

## Called from

- [api/domains/tick.php](/code/files/api/domains/tick.php.md)


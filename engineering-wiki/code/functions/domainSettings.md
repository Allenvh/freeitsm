# domainSettings()

Every setting, with its default applied where nothing is stored.

Cached for the rest of the request; pass `$fresh` = true after a save (the
settings endpoint does) so the answer reflects what was just written.

```php
function domainSettings(PDO $conn, bool $fresh = false): array
```

Defined in [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$fresh` | `bool` | `false` | true to re-read from the database instead of using this request's cached copy |

**Returns:** array<string,string>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 146 | `domainSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[domainSettingDefinitions()](/code/functions/domainSettingDefinitions.md)

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)
- [includes/domains/settings.php](/code/files/includes/domains/settings.php.md) - in [domainSetting()](/code/functions/domainSetting.md)
- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusProblems()](/code/functions/domainStatusProblems.md), [domainStatusRaise()](/code/functions/domainStatusRaise.md), [domainStatusRun()](/code/functions/domainStatusRun.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::runChecks`
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)


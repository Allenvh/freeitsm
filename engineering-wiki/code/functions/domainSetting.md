# domainSetting()

Returns one Domains setting as a string, from the cached settings (its default when not saved).

```php
function domainSetting(PDO $conn, string $key): string
```

Defined in [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | a key from `domainSettingDefinitions()`, or DOMAIN_SETTING_LAST_RUN; unknown gives '' |

**Returns:** `string`

## Calls

[domainSettings()](/code/functions/domainSettings.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)
- [api/domains/process.php](/code/files/api/domains/process.php.md)
- [api/domains/save.php](/code/files/api/domains/save.php.md)
- [api/domains/watch.php](/code/files/api/domains/watch.php.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsCreate()](/code/functions/apiDomainsCreate.md)
- [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md) - in [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainOpportunisticRun()](/code/functions/domainOpportunisticRun.md)
- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusState()](/code/functions/domainStatusState.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::applyLookup`, `DomainsService::revealAuthCode`


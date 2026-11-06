# domainAlertsDue()

Everything due today for expiry and certificates.

```php
function domainAlertsDue(PDO $conn, array $s): array
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$s` | `array` |  | every Domains setting, as `domainSettings()` returns them |

**Returns:** array<int,array> each: domain row fields + kind ('expiry'|'expired'|'ssl'), days, date, fingerprint

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `domainAlertsDue` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[domainParseDays()](/code/functions/domainParseDays.md)

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md)


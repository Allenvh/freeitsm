# domainScheduledRun()

The Domains scheduled job, within a time budget - registry lookups that are due, daily health checks, the weekly certificate and look-alike watches, then alerts, the expiry calendar and Service Status.

Stops starting new work when the budget runs low and says so in out_of_time.

```php
function domainScheduledRun(
    PDO $conn,
    float $budgetSeconds = 240,
    bool $includeWatches = true
): array
```

Defined in [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$budgetSeconds` | `float` | `240` | wall-clock seconds the run may use; 240 for cron, 25 for the page-load run |
| `$includeWatches` | `bool` | `true` | false skips the slow Certificate Transparency and look-alike scans |

**Returns:** array  counts for each stage, the alerts and calendar results, and the seconds taken

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `domainScheduledRun` | read [domains](/code/tables/domains.md) |  |
| 71 | `domainScheduledRun` | read [domains](/code/tables/domains.md) |  |

## Calls

[domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainCtScan()](/code/functions/domainCtScan.md), [domainLookalikeScan()](/code/functions/domainLookalikeScan.md), [domainLookup()](/code/functions/domainLookup.md), [domainLookupPace()](/code/functions/domainLookupPace.md), [domainSettings()](/code/functions/domainSettings.md), [domainStatusRun()](/code/functions/domainStatusRun.md), [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md), [domainWatchDue()](/code/functions/domainWatchDue.md), [domainWatchMark()](/code/functions/domainWatchMark.md)

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [cron/domains.php](/code/files/cron/domains.php.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainOpportunisticRun()](/code/functions/domainOpportunisticRun.md)


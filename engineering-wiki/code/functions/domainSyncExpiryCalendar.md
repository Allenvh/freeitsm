# domainSyncExpiryCalendar()

Both kinds. The name is kept: every caller already asks for "the calendar".

```php
function domainSyncExpiryCalendar(PDO $conn): array
```

Defined in [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[awcColumnExists()](/code/functions/awcColumnExists.md), [domainSetting()](/code/functions/domainSetting.md), [domainSyncCalendarKind()](/code/functions/domainSyncCalendarKind.md)

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [api/domains/statuses.php](/code/files/api/domains/statuses.php.md) - in [domainSyncAfterStatusChange()](/code/functions/domainSyncAfterStatusChange.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::afterDateChange`


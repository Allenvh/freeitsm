# calendarSyncConnectionFor()

The connection this analyst's calendar work goes through, or null.

```php
function calendarSyncConnectionFor(PDO $conn, array $enrolment): ?array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 213.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$enrolment` | `array` |  | the analyst's calendar_enrolments row as `calendarSyncEnrolment()` returns it |

**Returns:** `?array`

## Calls

[calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md), [calendarSyncOnlyConnection()](/code/functions/calendarSyncOnlyConnection.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md), [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md)


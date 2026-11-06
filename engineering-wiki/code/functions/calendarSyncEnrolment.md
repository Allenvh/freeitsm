# calendarSyncEnrolment()

One analyst's enrolment, or a synthetic "off" row when they have never chosen.

⚠️ NEVER returns null for a real analyst. A missing row means "has not decided
yet", which is off — and a caller forced to distinguish those two would sooner
or later treat one as the other.

calendar_address falls back to analysts.email, which is what it is for nearly
everyone; the column exists for the analyst whose login address is not their
mailbox.

```php
function calendarSyncEnrolment(PDO $conn, int $analystId): array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 301.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 310 | `calendarSyncEnrolment` | read [analysts](/code/tables/analysts.md) |  |
| 340 | `calendarSyncEnrolment` | read [calendar_enrolments](/code/tables/calendar_enrolments.md) |  |

## Calls

[calendarModeIsValid()](/code/functions/calendarModeIsValid.md), [calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md), [calendarSyncConnectionProvider()](/code/functions/calendarSyncConnectionProvider.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [taskCalendarModeIsValid()](/code/functions/taskCalendarModeIsValid.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md), [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md)


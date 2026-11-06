# calendarSyncPullForAnalyst()

Poll one analyst's calendar and apply what came back.

```php
function calendarSyncPullForAnalyst(PDO $conn, int $analystId): array
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array a small report, for the cron's output and for tests

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `calendarSyncPullForAnalyst` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`delta_token`, `delta_synced_datetime`) |  |
| 117 | `calendarSyncPullForAnalyst` | read [calendar_sync_events](/code/tables/calendar_sync_events.md), read [tickets](/code/tables/tickets.md) |  |
| 127 | `calendarSyncPullForAnalyst` | read [calendar_sync_events](/code/tables/calendar_sync_events.md), read [tasks](/code/tables/tasks.md) |  |
| 199 | `calendarSyncPullForAnalyst` | **delete** [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |

## Calls

[calendarAcceptDeletes()](/code/functions/calendarAcceptDeletes.md), [calendarPullApply()](/code/functions/calendarPullApply.md), [calendarPullApplyTaskDue()](/code/functions/calendarPullApplyTaskDue.md), [calendarPullApplyTaskWork()](/code/functions/calendarPullApplyTaskWork.md), [calendarPullUnschedule()](/code/functions/calendarPullUnschedule.md), [calendarPullUnscheduleTask()](/code/functions/calendarPullUnscheduleTask.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md)

## Called from

- [api/calendar/graph_notify.php](/code/files/api/calendar/graph_notify.php.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullAll()](/code/functions/calendarSyncPullAll.md)


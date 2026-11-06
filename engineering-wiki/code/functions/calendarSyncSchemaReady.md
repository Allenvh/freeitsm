# calendarSyncSchemaReady()

Does this database have the calendar-sync tables yet?

Same guard, and the same reasoning, as `scheduleSchemaReady()`: an install that
has pulled this code but not run Database Verification must simply not offer
the feature, rather than throwing "table doesn't exist" at somebody trying to
schedule a ticket. The push hangs off the schedule write path, which is
everyday work and must never be taken down by an unrelated missing table.

```php
function calendarSyncSchemaReady(PDO $conn): bool
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Called from

- [api/calendar/graph_notify.php](/code/files/api/calendar/graph_notify.php.md)
- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md)
- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullAll()](/code/functions/calendarSyncPullAll.md), [calendarSyncEnsureAllSubscriptions()](/code/functions/calendarSyncEnsureAllSubscriptions.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [calendarSyncRemoveAllForAnalyst()](/code/functions/calendarSyncRemoveAllForAnalyst.md)


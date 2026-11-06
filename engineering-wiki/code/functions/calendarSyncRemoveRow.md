# calendarSyncRemoveRow()

Remove one mapped event from the calendar it was written to, and forget it.

```php
function calendarSyncRemoveRow(PDO $conn, array $row): void
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 385.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | one calendar_sync_events row - connection_id, analyst_id, remote_calendar, remote_event_id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 409 | `calendarSyncRemoveRow` | **delete** [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |

## Calls

[calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md), [calendarSyncOnlyConnection()](/code/functions/calendarSyncOnlyConnection.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) - in [csTakeBack()](/code/functions/csTakeBack.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [calendarSyncRemoveAllForAnalyst()](/code/functions/calendarSyncRemoveAllForAnalyst.md)


# calendarPullApplyTaskWork()

A task's work window, moved in the calendar.

```php
function calendarPullApplyTaskWork(PDO $conn, array $row, array $change, string $address): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 284.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the calendar_sync_events row joined to its task's work_* columns, as the poll loads it |
| `$change` | `array` |  | one 'changed' entry from `pollChanges()` - remote_event_id, start, end (naive), all_day |
| `$address` | `string` |  | the analyst's calendar address, quoted in the audit text only |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 287 | `calendarPullApplyTaskWork` | **update** [tasks](/code/tables/tasks.md) (`work_start_datetime`, `work_end_datetime`, `work_all_day`, `updated_datetime`) |  |

## Calls

[calendarPullTaskAudit()](/code/functions/calendarPullTaskAudit.md)

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md)


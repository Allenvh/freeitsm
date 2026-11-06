# calendarPullUnscheduleTask()

The event was deleted from the calendar: take the matching field away.

```php
function calendarPullUnscheduleTask(PDO $conn, array $row, int $analystId, string $address): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 323.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the calendar_sync_events row joined to its task - 'kind' says whether due_date or the work window is cleared |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$address` | `string` |  | the analyst's calendar address, quoted in the audit text only |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 327 | `calendarPullUnscheduleTask` | **update** [tasks](/code/tables/tasks.md) (`due_date`, `updated_datetime`) |  |
| 334 | `calendarPullUnscheduleTask` | **update** [tasks](/code/tables/tasks.md) (`work_start_datetime`, `work_end_datetime`, `updated_datetime`) |  |

## Calls

[calendarPullTaskAudit()](/code/functions/calendarPullTaskAudit.md)

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md)


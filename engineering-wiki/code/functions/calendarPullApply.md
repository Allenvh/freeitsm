# calendarPullApply()

Apply a moved event to its ticket.

```php
function calendarPullApply(PDO $conn, array $row, array $change, string $address): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 217.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the calendar_sync_events row joined to its ticket's work_* columns, as the poll loads it |
| `$change` | `array` |  | one 'changed' entry from `pollChanges()` - remote_event_id, start, end (naive), all_day |
| `$address` | `string` |  | the analyst's calendar address, quoted in the audit text only |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 220 | `calendarPullApply` | **update** [tickets](/code/tables/tickets.md) (`work_start_datetime`, `work_end_datetime`, `work_all_day`) |  |
| 227 | `calendarPullApply` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`updated_datetime`) |  |

## Calls

[calendarPullAudit()](/code/functions/calendarPullAudit.md)

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md)


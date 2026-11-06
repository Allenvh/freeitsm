# calendarPullUnschedule()

Clear a ticket's schedule because its event was deleted.

```php
function calendarPullUnschedule(PDO $conn, int $ticketId, int $analystId, string $address): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 236.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$address` | `string` |  | the analyst's calendar address, quoted in the audit text only |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 238 | `calendarPullUnschedule` | read [tickets](/code/tables/tickets.md) |  |
| 240 | `calendarPullUnschedule` | **update** [tickets](/code/tables/tickets.md) (`work_start_datetime`, `work_end_datetime`, `work_all_day`) |  |

## Calls

[calendarPullAudit()](/code/functions/calendarPullAudit.md)

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md)


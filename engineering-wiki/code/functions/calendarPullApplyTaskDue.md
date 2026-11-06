# calendarPullApplyTaskDue()

A task's DUE DATE, moved in the calendar.

⚠️ A due date is a commitment rather than a plan — it is often a promise to
somebody else. Ed asked for it to be editable from Outlook anyway, so it is;
the audit row is what makes that safe to live with, because otherwise a
deadline could move and nobody would ever know where it went.

```php
function calendarPullApplyTaskDue(PDO $conn, array $row, string $newDay, string $address): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 308.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the calendar_sync_events row (kind 'due') joined to its task's due_date |
| `$newDay` | `string` |  | the new due date, 'Y-m-d', taken from the moved event's start |
| `$address` | `string` |  | the analyst's calendar address, quoted in the audit text only |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 310 | `calendarPullApplyTaskDue` | **update** [tasks](/code/tables/tasks.md) (`due_date`, `updated_datetime`) |  |

## Calls

[calendarPullTaskAudit()](/code/functions/calendarPullTaskAudit.md)

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md)


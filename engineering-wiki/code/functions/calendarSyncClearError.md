# calendarSyncClearError()

Clears the analyst's sync error and stamps the time of the last good sync; never throws.

```php
function calendarSyncClearError(PDO $conn, int $analystId): void
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 502.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

**Writes:** calendar_enrolments(last_error, last_sync_datetime)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 506 | `calendarSyncClearError` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`last_error`, `last_sync_datetime`) |  |

## Called from

- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md)


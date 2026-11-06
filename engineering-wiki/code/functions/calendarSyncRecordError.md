# calendarSyncRecordError()

Stores a sync error (cut to 500 characters) on the analyst's enrolment, creating an 'off' enrolment row when there is none; never throws.

```php
function calendarSyncRecordError(PDO $conn, int $analystId, string $message): void
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 486.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$message` | `string` |  | the error to show the analyst; cut to 500 characters |

**Returns:** `void`

**Writes:** calendar_enrolments(last_error) - shown on the analyst's calendar settings

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 490 | `calendarSyncRecordError` | **insert** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`analyst_id`, `mode`, `last_error`) |  |

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncPullForAnalyst()](/code/functions/calendarSyncPullForAnalyst.md), [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [calendarSyncReconcileTask()](/code/functions/calendarSyncReconcileTask.md), [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md)


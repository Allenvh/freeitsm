# calendarPullTaskAudit()

One line of task history, marked as having come from a calendar.

```php
function calendarPullTaskAudit(
    PDO $conn,
    int $taskId,
    int $analystId,
    string $field,
    string $old,
    string $new
): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 349.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the field name shown in the task history - 'Scheduled' or 'Due date' |
| `$old` | `string` |  | the value before; cut to 500 characters |
| `$new` | `string` |  | the value after, with a note of where it came from; cut to 500 characters |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 353 | `calendarPullTaskAudit` | **insert** [task_audit](/code/tables/task_audit.md) (`task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarPullApplyTaskWork()](/code/functions/calendarPullApplyTaskWork.md), [calendarPullApplyTaskDue()](/code/functions/calendarPullApplyTaskDue.md), [calendarPullUnscheduleTask()](/code/functions/calendarPullUnscheduleTask.md)


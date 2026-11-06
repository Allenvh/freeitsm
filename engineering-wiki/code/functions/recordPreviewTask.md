# recordPreviewTask()

── Task ────────────────────────────────────────────────────────────────────

```php
function recordPreviewTask(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the tasks id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 188 | `recordPreviewTask` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |

## Calls

[analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [rpField()](/code/functions/rpField.md), [rpFields()](/code/functions/rpFields.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*


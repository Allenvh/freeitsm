# apiSerializeTask()

Shapes a task row for the API, with its tags and subtask progress (total and done) read alongside.

```php
function apiSerializeTask(PDO $conn, array $r): array
```

Defined in [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the tasks row as `apiTaskSelect()` loads it, with the lookup and people names |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 74 | `apiSerializeTask` | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |
| 80 | `apiSerializeTask` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiTaskContractor()](/code/functions/apiTaskContractor.md)

## Called from

- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksList()](/code/functions/apiTasksList.md), [apiTasksGet()](/code/functions/apiTasksGet.md), [apiTasksCreate()](/code/functions/apiTasksCreate.md), [apiTasksUpdate()](/code/functions/apiTasksUpdate.md), [apiTasksMove()](/code/functions/apiTasksMove.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`


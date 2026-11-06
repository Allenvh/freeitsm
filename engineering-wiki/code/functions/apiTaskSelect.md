# apiTaskSelect()

The SELECT for tasks, with status, priority, analyst, team and creator names.

```php
function apiTaskSelect(): string
```

Defined in [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), line 41.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `apiTaskSelect` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |

## Called from

- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiLoadTask()](/code/functions/apiLoadTask.md), [apiTasksList()](/code/functions/apiTasksList.md)


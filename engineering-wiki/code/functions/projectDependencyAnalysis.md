# projectDependencyAnalysis()

Per task: depends_on [{id, lag}], waiting_on [ids], clash (bool), critical (bool), slack (days or null). `$tasks` need id, start_date, due_date, is_closed.

```php
function projectDependencyAnalysis(array $tasks, array $deps): array
```

Defined in [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), line 74.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tasks` | `array` |  | the project's tasks - id, start_date, due_date, is_closed |
| `$deps` | `array` |  | the project's dependencies: task_id, depends_on_id, lag_days |

**Returns:** `array`

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)


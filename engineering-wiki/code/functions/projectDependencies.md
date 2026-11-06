# projectDependencies()

A project's dependencies - both ends inside the project: [{id, task_id, depends_on_id, lag_days}].

```php
function projectDependencies(PDO $conn, int $projectId): array
```

Defined in [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), line 33.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 | `projectDependencies` | read [task_dependencies](/code/tables/task_dependencies.md), read [tasks](/code/tables/tasks.md) |  |

## Calls

[projectDependenciesReady()](/code/functions/projectDependenciesReady.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addTaskDependency`, `ProjectToolsService::removeTaskDependency`


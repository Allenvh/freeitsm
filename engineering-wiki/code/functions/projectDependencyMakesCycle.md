# projectDependencyMakesCycle()

Would task -> dependsOn close a loop (dependsOn already waits, directly or not, for task)?

```php
function projectDependencyMakesCycle(array $deps, int $taskId, int $dependsOnId): bool
```

Defined in [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), line 52.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$deps` | `array` |  | the project's dependencies as `projectDependencies()` returns them (task_id, depends_on_id) |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$dependsOnId` | `int` |  | the task it would wait for |

**Returns:** `bool`

## Called from

- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addTaskDependency`


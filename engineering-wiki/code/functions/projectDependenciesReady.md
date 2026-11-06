# projectDependenciesReady()

Projects - task dependencies and the critical path (3.3.0).

A dependency is FINISH-TO-START: task_id cannot start until depends_on_id
has finished, plus lag_days. Both tasks belong to the same project (checked
when it is made; a task later moved out of the project is simply left out on
read). Everything else is worked out, never stored:

  waiting_on  an OPEN task whose predecessor is not finished - "blocked by a
              dependency", in the status breakdown and on the Plan
  clash       the task is planned to start before its predecessor (plus the
              lag) is due to finish - the plan cannot be true as written
  critical    on the critical path: no slack. A forward pass (earliest start
              = the later of its own start and every predecessor's finish +
              lag + 1) and a backward pass from the latest finish; slack =
              latest start - earliest start. Only dated tasks take part.

Cycles are refused when a dependency is made (projectDependencyMakesCycle).

```php
function projectDependenciesReady(PDO $conn): bool
```

Defined in [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), line 22.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `projectDependenciesReady` | read [task_dependencies](/code/tables/task_dependencies.md) |  |

## Called from

- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md) - in [projectDependencies()](/code/functions/projectDependencies.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addTaskDependency`


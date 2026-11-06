# includes/projects/dependencies.php

> PHP - 127 lines

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

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectDependenciesReady()](/code/functions/projectDependenciesReady.md) | 22 | Projects - task dependencies and the critical path (3.3.0). |
| [projectDependencies()](/code/functions/projectDependencies.md) | 33 | A project's dependencies - both ends inside the project: [{id, task_id, depends_on_id, lag_days}]. |
| [projectDependencyMakesCycle()](/code/functions/projectDependencyMakesCycle.md) | 52 | Would task -> dependsOn close a loop (dependsOn already waits, directly or not, for task)? |
| [projectDependencyAnalysis()](/code/functions/projectDependencyAnalysis.md) | 74 | Per task: depends_on [{id, lag}], waiting_on [ids], clash (bool), critical (bool), slack (days or null). `$tasks` need id, start_date, due_date, is_closed. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `projectDependenciesReady` | read [task_dependencies](/code/tables/task_dependencies.md) |  |
| 36 | `projectDependencies` | read [task_dependencies](/code/tables/task_dependencies.md), read [tasks](/code/tables/tasks.md) |  |

## How it hangs together

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)


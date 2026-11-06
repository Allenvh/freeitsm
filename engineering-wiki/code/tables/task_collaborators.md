# task_collaborators

The other people on a task (GH #89) — shown as "Involved". Modelled on change_cab_members: Changes already solved "several named people on one record, each with their own state", so this is the same shape rather than a new one. See includes/services/tasks.php for the rules.  🔴 THE OWNER IS NOT IN HERE. Accountability stays in tasks.assigned_analyst_id and nowhere else — two homes would be two things that can disagree. The consequence is that the existing column keeps its exact meaning, so the REST contract, stored workflows and board grouping are unchanged BY DEFINITION rather than by inspection.  ⚠️ is_completed is only ever SHOWN when the `tasks_collaborator_completion` setting is on, but it is recorded regardless — turning the setting off must hide ticks, never destroy them, exactly as narrowing `tasks_time_scope` hides hours somebody recorded rather than deleting them.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `task_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `is_completed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `completed_datetime` | `DATETIME NULL` |  |
| `added_by_id` | `INT NULL` |  |
| `added_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):764 | `TasksService::addCollaborator` | insert | `task_id`, `analyst_id`, `added_by_id`, `added_datetime` |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):777 | `TasksService::removeCollaborator` | delete |  |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):812 | `TasksService::setCollaboratorDone` | update | `is_completed`, `completed_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L79 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTasksList L212, apiTasksList L216 |  |
| [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) | notificationsAudienceFor L165 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::collaboratorsForMany L715, TasksService::addCollaborator L757, TasksService::setCollaboratorDone L820, TasksService::collaboratorsOutstanding L834 |  |


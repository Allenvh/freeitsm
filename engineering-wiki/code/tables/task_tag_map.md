# task_tag_map

## Columns

| Column | Definition | Notes |
|---|---|---|
| `task_id` | `INT NOT NULL` |  |
| `tag_id` | `INT NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):560 | `TasksService::deleteTask` | delete |  |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):1469 | `TasksService::syncTags` | delete |  |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):1470 | `TasksService::syncTags` | insert | `task_id`, `tag_id` |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md):764 | `TaskRecurrence::createOccurrence` | insert | `task_id`, `tag_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L136 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L247 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiSerializeTask L74, apiTasksList L231, apiTaskTagsList L492 |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md) | TaskRecurrence::createOccurrence L764 |  |


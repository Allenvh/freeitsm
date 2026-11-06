# task_priorities

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3397 |  | insert | `name`, `colour`, `is_default`, `display_order` |  |
| [api/tasks/delete_task_priority.php](/code/files/api/tasks/delete_task_priority.php.md):41 |  | delete |  |  |
| [api/tasks/save_task_priority.php](/code/files/api/tasks/save_task_priority.php.md):38 |  | update | `is_default` |  |
| [api/tasks/save_task_priority.php](/code/files/api/tasks/save_task_priority.php.md):44 |  | update | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/tasks/save_task_priority.php](/code/files/api/tasks/save_task_priority.php.md):50 |  | insert | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/tasks/save_task_priority.php](/code/files/api/tasks/save_task_priority.php.md):58 |  | update | `is_default` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/lookups.php](/code/files/api/projects/lookups.php.md) | L62 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3395 |  |
| [api/tasks/delete_task_priority.php](/code/files/api/tasks/delete_task_priority.php.md) | L27, L40 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L39, L107 |  |
| [api/tasks/get_task_priorities.php](/code/files/api/tasks/get_task_priorities.php.md) | L20 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L125, L223 |  |
| [api/tasks/save_task_priority.php](/code/files/api/tasks/save_task_priority.php.md) | L56 |  |
| [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md) | L122 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTaskSelect L42, apiTasksList L277, apiTaskPrioritiesList L476 |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTask L184 |  |
| [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) | notificationsTaskAssignedEmail L446 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectDetail L397 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_create_task L2605 |  |


# task_statuses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `is_closed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3384 |  | insert | `name`, `is_closed`, `colour`, `is_default`, `display_order` |  |
| [api/tasks/delete_task_status.php](/code/files/api/tasks/delete_task_status.php.md):41 |  | delete |  |  |
| [api/tasks/reorder_task_statuses.php](/code/files/api/tasks/reorder_task_statuses.php.md):26 |  | update | `display_order` |  |
| [api/tasks/save_task_status.php](/code/files/api/tasks/save_task_status.php.md):39 |  | update | `is_default` |  |
| [api/tasks/save_task_status.php](/code/files/api/tasks/save_task_status.php.md):45 |  | update | `name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active` |  |
| [api/tasks/save_task_status.php](/code/files/api/tasks/save_task_status.php.md):51 |  | insert | `name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active` |  |
| [api/tasks/save_task_status.php](/code/files/api/tasks/save_task_status.php.md):59 |  | update | `is_default` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/lookups.php](/code/files/api/projects/lookups.php.md) | L61 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3382 |  |
| [api/tasks/delete_task_status.php](/code/files/api/tasks/delete_task_status.php.md) | L27, L40 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L39, L107 |  |
| [api/tasks/get_task_statuses.php](/code/files/api/tasks/get_task_statuses.php.md) | L20 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L125, L198, L223, L297 |  |
| [api/tasks/reorder.php](/code/files/api/tasks/reorder.php.md) | L42, L55 |  |
| [api/tasks/save_task_status.php](/code/files/api/tasks/save_task_status.php.md) | L57 |  |
| [api/tasks/toggle_subtask.php](/code/files/api/tasks/toggle_subtask.php.md) | L41, L69, L72 |  |
| [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) | enrolReconcileTasks L94 |  |
| [api/tickets/get_ticket_tasks.php](/code/files/api/tickets/get_ticket_tasks.php.md) | L44 |  |
| [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md) | L122 |  |
| [api/tickets/search_linkable_tasks.php](/code/files/api/tickets/search_linkable_tasks.php.md) | L50 |  |
| [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) | apiProjectStages L316, apiProjectTasks L656 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTaskSelect L42, apiSerializeTask L80, apiTasksList L277, apiTasksGet L319, apiTaskStatusesList L457 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystReferenceRegistry L116, analystReferenceRegistry L127 |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTask L184 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleContractorTasks L793 |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L156, projectAiFacts L251 |  |
| [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) | projectAssistTasks L278 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatMaturity L126, projectChatRead L255 |  |
| [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) | projectLabourToCome L285, projectEarnedValue L384 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L136 |  |
| [includes/projects/flow.php](/code/files/includes/projects/flow.php.md) | projectFlow L63 |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) | projectAlertsOverdueDigest L39 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectTaskStats L37, projectDetail L384, projectDetail L397 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewTask L188 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::raid L844 |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md) | TaskRecurrence::defaultOpenStatusId L840 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::loadTaskRow L939 |  |
| [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) | tasksContractorReminders L207 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L786, getWatchtowerData L796, getWatchtowerData L813 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_create_task L2600, WorkflowEngine::action_create_task L2601 |  |


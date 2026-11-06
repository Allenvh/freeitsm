# task_checklists

Checklists on TASKS (discussion #138, round three) - the same shape as the two ticket tables above, keyed on a task instead. Only used while Tasks → Settings → Checklists is switched on.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `task_id` | `INT NOT NULL` |  |
| `template_id` | `INT DEFAULT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `closure_mode` | `ENUM('warn','block') NOT NULL DEFAULT 'warn'` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT DEFAULT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):547 | `ChecklistsService::copyTemplateToTask` | insert | `task_id`, `template_id`, `title`, `closure_mode`, `sort_order`, `created_by_id`, `created_datetime` |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):589 | `ChecklistsService::reorderTaskChecklists` | update | `sort_order` |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):689 | `ChecklistsService::removeTaskChecklist` | delete |  |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):713 | `ChecklistsService::deleteForTasks` | delete |  |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):830 | `ChecklistsService::copyTaskChecklists` | insert | `task_id`, `template_id`, `title`, `closure_mode`, `sort_order`, `created_by_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md) | ChecklistsService::taskChecklists L472, ChecklistsService::nextTaskChecklistOrder L575, ChecklistsService::taskIdForItem L616, ChecklistsService::taskIdForChecklist L628, ChecklistsService::deleteForTasks L710, ChecklistsService::taskOutstandingMandatorySteps L725 ... |  |


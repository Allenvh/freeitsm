# task_audit

Tasks had NO history at all until calendar sync could change one from outside FreeITSM (#75). Mirrors ticket_audit exactly rather than inventing a second shape for the same idea.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `task_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` | NULL = not a person: a sync, a cron |
| `field_name` | `VARCHAR(100) NOT NULL` |  |
| `old_value` | `VARCHAR(500) NULL` |  |
| `new_value` | `VARCHAR(500) NULL` |  |
| `source` | `VARCHAR(20) NOT NULL DEFAULT 'app'` | app \| calendar |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md):353 | `calendarPullTaskAudit` | insert | `task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime` |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):792 | `ChecklistsService::recordTaskCompletionOverride` | insert | `task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime` |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):1093 | `TasksService::moveTaskToCompany` | insert | `task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime` |  |


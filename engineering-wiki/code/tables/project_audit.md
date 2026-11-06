# project_audit

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `field_name` | `VARCHAR(100) NOT NULL` |  |
| `old_value` | `VARCHAR(1000) NULL` |  |
| `new_value` | `VARCHAR(1000) NULL` |  |
| `source` | `VARCHAR(20) NOT NULL DEFAULT 'app'` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):1038 | `ProjectsService::audit` | insert | `project_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) | apiProjectHistory L693, apiProjectHistory L696 |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L309 |  |
| [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) | projectAssistOverview L215 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectDetail L414 |  |


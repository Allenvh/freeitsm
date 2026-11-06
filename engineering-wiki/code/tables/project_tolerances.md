# project_tolerances

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `stage_id` | `INT NULL` |  |
| `dimension` | `VARCHAR(12) NOT NULL` |  |
| `value` | `INT NOT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):124 | `ProjectTemplatesService::createFromTemplate` | insert | `project_id`, `stage_id`, `dimension`, `value` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):706 | `ProjectToolsService::saveTolerances` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):715 | `ProjectToolsService::saveTolerances` | update | `value` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):717 | `ProjectToolsService::saveTolerances` | insert | `project_id`, `stage_id`, `dimension`, `value` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectsPhase2Ready L199, projectExceptionColumns L209 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::saveTolerances L712, ProjectToolsService::tolerances L730 |  |


# task_dependencies

Task dependencies (3.3.0) - includes/projects/dependencies.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `task_id` | `INT NOT NULL` |  |
| `depends_on_id` | `INT NOT NULL` |  |
| `lag_days` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):96 | `ProjectTemplatesService::createFromTemplate` | insert | `task_id`, `depends_on_id`, `lag_days`, `created_by_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1192 | `ProjectToolsService::addTaskDependency` | insert | `task_id`, `depends_on_id`, `lag_days`, `created_by_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1213 | `ProjectToolsService::removeTaskDependency` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md) | projectDependenciesReady L26, projectDependencies L36 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplateCapture L464 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::addTaskDependency L1195 |  |


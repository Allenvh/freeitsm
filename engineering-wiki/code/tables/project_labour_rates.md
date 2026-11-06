# project_labour_rates

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `scope` | `VARCHAR(10) NOT NULL` |  |
| `ref_id` | `INT NULL` |  |
| `hourly_rate` | `DECIMAL(12,2) NOT NULL` |  |
| `effective_from` | `DATE NOT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/projects/settings.php](/code/files/api/projects/settings.php.md):102 |  | insert | `scope`, `ref_id`, `hourly_rate`, `effective_from`, `created_by_id`, `created_datetime` |  |
| [api/projects/settings.php](/code/files/api/projects/settings.php.md):105 |  | delete |  |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):280 | `ProjectsService::deleteProject` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1960 | `ProjectToolsService::addProjectRate` | insert | `scope`, `ref_id`, `hourly_rate`, `effective_from`, `created_by_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1976 | `ProjectToolsService::deleteProjectRate` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/settings.php](/code/files/api/projects/settings.php.md) | projectRatesForSettings L43 |  |
| [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) | projectBudgetReady L61, projectLabourRates L97 |  |


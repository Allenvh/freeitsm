# project_budget_lines

Budget (3.2.0) - includes/projects/budget.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `title` | `VARCHAR(200) NOT NULL` |  |
| `category` | `VARCHAR(20) NOT NULL DEFAULT 'other'` |  |
| `planned_amount` | `DECIMAL(18,2) NULL` |  |
| `actual_amount` | `DECIMAL(18,2) NULL` |  |
| `contract_id` | `INT NULL` |  |
| `cost_centre_id` | `INT NULL` |  |
| `notes` | `VARCHAR(500) NULL` |  |
| `planned_date` | `DATE NULL` | 3.3.0: budget over time and the forecast. |
| `spent_date` | `DATE NULL` |  |
| `forecast_amount` | `DECIMAL(18,2) NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1636 | `ProjectToolsService::decideChangeRequest` | insert | `project_id`, `title`, `category`, `planned_amount`, `notes`, `position`, `created_by_id`, `created_datetime`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1889 | `ProjectToolsService::saveBudgetLine` | update | `title`, `category`, `planned_amount`, `actual_amount`, `contract_id`, `cost_centre_id`, `notes`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1895 | `ProjectToolsService::saveBudgetLine` | insert | `project_id`, `title`, `category`, `planned_amount`, `actual_amount`, `contract_id`, `cost_centre_id`, `notes`, `position`, `created_by_id` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1918 | `ProjectToolsService::budgetLineWhen` | update | `planned_date`, `spent_date`, `forecast_amount` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1936 | `ProjectToolsService::deleteBudgetLine` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md) | costCentreUsage L165 |  |
| [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) | projectBudgetReady L61, projectBudgetTotals L184, projectBudgetDetail L441 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectPlanSnapshot L87 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::decideChangeRequest L1635, ProjectToolsService::saveBudgetLine L1880, ProjectToolsService::saveBudgetLine L1886, ProjectToolsService::saveBudgetLine L1894, ProjectToolsService::deleteBudgetLine L1932 |  |


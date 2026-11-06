# project_benefit_measures

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `benefit_id` | `INT NOT NULL` |  |
| `value` | `DECIMAL(18,2) NOT NULL` |  |
| `measured_date` | `DATE NOT NULL` |  |
| `note` | `VARCHAR(500) NULL` |  |
| `recorded_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):264 | `ProjectsService::deleteProject` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1442 | `ProjectToolsService::deleteBenefit` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1465 | `ProjectToolsService::addBenefitMeasure` | insert | `benefit_id`, `value`, `measured_date`, `note`, `recorded_by_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1488 | `ProjectToolsService::deleteBenefitMeasure` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) | projectBenefitsReady L38, projectBenefits L85 |  |


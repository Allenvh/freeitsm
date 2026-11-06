# project_benefits

Benefits realisation (3.3.0) - includes/projects/benefits.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `title` | `VARCHAR(200) NOT NULL` |  |
| `measure` | `VARCHAR(255) NULL` |  |
| `unit` | `VARCHAR(30) NULL` |  |
| `direction` | `VARCHAR(4) NOT NULL DEFAULT 'up'` |  |
| `baseline_value` | `DECIMAL(18,2) NULL` |  |
| `target_value` | `DECIMAL(18,2) NULL` |  |
| `target_date` | `DATE NULL` |  |
| `owner_analyst_id` | `INT NULL` |  |
| `review_date` | `DATE NULL` |  |
| `review_months` | `INT NULL` |  |
| `status` | `VARCHAR(10) NOT NULL DEFAULT 'open'` |  |
| `notes` | `TEXT NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):104 | `ProjectTemplatesService::createFromTemplate` | insert | `project_id`, `title`, `measure`, `unit`, `direction`, `target_value`, `target_date`, `review_date`, `review_months`, `status` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1414 | `ProjectToolsService::saveBenefit` | update | `title`, `measure`, `unit`, `direction`, `baseline_value`, `target_value`, `target_date`, `owner_analyst_id`, `review_date`, `review_months` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1422 | `ProjectToolsService::saveBenefit` | insert | `title`, `measure`, `unit`, `direction`, `baseline_value`, `target_value`, `target_date`, `owner_analyst_id`, `review_date`, `review_months` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1443 | `ProjectToolsService::deleteBenefit` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1471 | `ProjectToolsService::addBenefitMeasure` | update | `review_date`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) | projectBenefitsReady L38, projectBenefits L78, projectBenefitStats L123, projectAlertsBenefits L166 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplateCapture L495 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::saveBenefit L1421, ProjectToolsService::benefit L1502 |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md) | ProjectsService::deleteProject L264 |  |


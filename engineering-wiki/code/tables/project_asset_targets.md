# project_asset_targets

Asset targets (3.2.0): live progress measured from Assets.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `scope` | `VARCHAR(10) NOT NULL DEFAULT 'filter'` |  |
| `scope_type_id` | `INT NULL` |  |
| `scope_field` | `VARCHAR(30) NULL` |  |
| `scope_value` | `VARCHAR(100) NULL` |  |
| `done_field` | `VARCHAR(30) NOT NULL` |  |
| `done_op` | `VARCHAR(20) NOT NULL` |  |
| `done_value` | `VARCHAR(100) NULL` |  |
| `target_date` | `DATE NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):131 | `ProjectTemplatesService::createFromTemplate` | insert | `project_id`, `name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):895 | `ProjectToolsService::saveTarget` | update | `name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):903 | `ProjectToolsService::saveTarget` | insert | `project_id`, `name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):923 | `ProjectToolsService::deleteTarget` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetsReady L35, projectTargetsFor L199 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplateCapture L508 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::saveTarget L901, ProjectToolsService::target L944 |  |


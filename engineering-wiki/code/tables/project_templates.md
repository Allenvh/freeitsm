# project_templates

Project templates saved from a project (3.2.0); built-ins live in code.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `content` | `MEDIUMTEXT NOT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):200 | `ProjectTemplatesService::saveFromProject` | update | `name`, `description`, `content`, `updated_datetime` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):204 | `ProjectTemplatesService::saveFromProject` | insert | `name`, `description`, `content`, `is_active`, `created_by_analyst_id`, `created_datetime`, `updated_datetime` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):224 | `ProjectTemplatesService::update` | update | `name`, `description`, `is_active`, `updated_datetime` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):238 | `ProjectTemplatesService::delete` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplatesReady L62, projectTemplateList L367, projectTemplateLoad L395 |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) | ProjectTemplatesService::row L288 |  |


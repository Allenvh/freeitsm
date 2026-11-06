# project_gate_items

Gate checklists (3.3.0) - includes/projects/gatecheck.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `stage_id` | `INT NOT NULL` |  |
| `kind` | `VARCHAR(10) NOT NULL DEFAULT 'check'` |  |
| `title` | `VARCHAR(200) NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `change_id` | `INT NULL` |  |
| `document_id` | `INT NULL` |  |
| `done_by_id` | `INT NULL` |  |
| `done_datetime` | `DATETIME NULL` |  |
| `notes` | `VARCHAR(500) NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):409 | `ProjectsService::deleteStage` | delete |  |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):82 | `ProjectTemplatesService::createFromTemplate` | insert | `project_id`, `stage_id`, `kind`, `title`, `position`, `created_by_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1252 | `ProjectToolsService::saveGateItem` | update | `title`, `analyst_id`, `change_id`, `notes` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1257 | `ProjectToolsService::saveGateItem` | insert | `project_id`, `stage_id`, `kind`, `title`, `analyst_id`, `change_id`, `notes`, `position`, `created_by_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1277 | `ProjectToolsService::deleteGateItem` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1303 | `ProjectToolsService::tickGateItem` | update | `done_by_id`, `done_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1312 | `ProjectToolsService::tickGateItem` | update | `document_id`, `done_by_id`, `done_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1318 | `ProjectToolsService::tickGateItem` | update | `done_by_id`, `done_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) | projectGateItemsReady L35, projectGateItems L72 |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) | projectAlertsStalled L119 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplateCapture L446 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::saveGateItem L1256, ProjectToolsService::setGateKind L1341, ProjectToolsService::gateItem L1365 |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md) | ProjectsService::signoffEvent L841 |  |


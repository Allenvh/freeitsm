# project_change_requests

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `number` | `INT NOT NULL` |  |
| `title` | `VARCHAR(200) NOT NULL` |  |
| `description` | `TEXT NULL` |  |
| `reason` | `TEXT NULL` |  |
| `impact_days` | `INT NULL` |  |
| `impact_cost` | `DECIMAL(14,2) NULL` |  |
| `impact_scope` | `VARCHAR(1000) NULL` |  |
| `status` | `VARCHAR(12) NOT NULL DEFAULT 'proposed'` |  |
| `raised_by_id` | `INT NULL` |  |
| `raised_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `decided_by_id` | `INT NULL` |  |
| `decided_datetime` | `DATETIME NULL` |  |
| `decision_notes` | `TEXT NULL` |  |
| `applied` | `TEXT NULL` |  |
| `baseline_id` | `INT NULL` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1571 | `ProjectToolsService::saveChangeRequest` | update | `title`, `description`, `reason`, `impact_days`, `impact_cost`, `impact_scope`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1578 | `ProjectToolsService::saveChangeRequest` | insert | `project_id`, `number`, `title`, `description`, `reason`, `impact_days`, `impact_cost`, `impact_scope`, `status`, `raised_by_id` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1616 | `ProjectToolsService::decideChangeRequest` | update | `status`, `decided_by_id`, `decided_datetime`, `decision_notes`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1645 | `ProjectToolsService::decideChangeRequest` | update | `baseline_id`, `applied` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1672 | `ProjectToolsService::withdrawChangeRequest` | update | `status`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatMaturity L135 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectControlReady L43, projectChangeStats L298, projectControlDetail L315, projectControlDetail L333 |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) | projectAlertsStalled L104 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::saveChangeRequest L1577, ProjectToolsService::changeRequest L1685 |  |


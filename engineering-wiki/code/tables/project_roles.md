# project_roles

Projects phase 2 (3.2.0): roles, members, scope, RACI, RAID, tolerances.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/projects/settings.php](/code/files/api/projects/settings.php.md):124 |  | update | `name`, `description`, `is_active` |  |
| [api/projects/settings.php](/code/files/api/projects/settings.php.md):127 |  | insert | `name`, `description`, `display_order`, `is_active` |  |
| [api/projects/settings.php](/code/files/api/projects/settings.php.md):134 |  | delete |  |  |
| [api/projects/settings.php](/code/files/api/projects/settings.php.md):138 |  | update | `display_order` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):812 |  | insert | `name`, `description`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/lookups.php](/code/files/api/projects/lookups.php.md) | L26 |  |
| [api/projects/settings.php](/code/files/api/projects/settings.php.md) | projectRolesForSettings L31, L120, L126 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L811 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleProjects L492 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatApplyOne L637 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::members L1124, ProjectToolsService::roleId L1770, ProjectToolsService::roleName L1784 |  |


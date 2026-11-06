# change_priorities

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/delete_change_priority.php](/code/files/api/change-management/delete_change_priority.php.md):43 |  | delete |  |  |
| [api/change-management/save_change_priority.php](/code/files/api/change-management/save_change_priority.php.md):40 |  | update | `is_default` |  |
| [api/change-management/save_change_priority.php](/code/files/api/change-management/save_change_priority.php.md):46 |  | update | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/change-management/save_change_priority.php](/code/files/api/change-management/save_change_priority.php.md):52 |  | insert | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/change-management/save_change_priority.php](/code/files/api/change-management/save_change_priority.php.md):60 |  | update | `is_default` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2468 |  | insert | `name`, `colour`, `is_default`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/delete_change_priority.php](/code/files/api/change-management/delete_change_priority.php.md) | L29, L42 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L34 |  |
| [api/change-management/get_approvals.php](/code/files/api/change-management/get_approvals.php.md) | L25 |  |
| [api/change-management/get_calendar_changes.php](/code/files/api/change-management/get_calendar_changes.php.md) | L31 |  |
| [api/change-management/get_change_priorities.php](/code/files/api/change-management/get_change_priorities.php.md) | L20 |  |
| [api/change-management/list.php](/code/files/api/change-management/list.php.md) | L40 |  |
| [api/change-management/save_change_priority.php](/code/files/api/change-management/save_change_priority.php.md) | L58 |  |
| [api/problem-management/list_linkable_changes.php](/code/files/api/problem-management/list_linkable_changes.php.md) | L32 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2466 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeSelect L57, apiChangesList L258 |  |
| [includes/feature_bingo/cards/changes.php](/code/files/includes/feature_bingo/cards/changes.php.md) | L58 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::loadJoined L746 |  |


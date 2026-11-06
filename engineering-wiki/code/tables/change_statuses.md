# change_statuses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `is_closed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/delete_change_status.php](/code/files/api/change-management/delete_change_status.php.md):44 |  | delete |  |  |
| [api/change-management/save_change_status.php](/code/files/api/change-management/save_change_status.php.md):41 |  | update | `is_default` |  |
| [api/change-management/save_change_status.php](/code/files/api/change-management/save_change_status.php.md):47 |  | update | `name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active` |  |
| [api/change-management/save_change_status.php](/code/files/api/change-management/save_change_status.php.md):53 |  | insert | `name`, `colour`, `is_closed`, `is_default`, `display_order`, `is_active` |  |
| [api/change-management/save_change_status.php](/code/files/api/change-management/save_change_status.php.md):61 |  | update | `is_default` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2449 |  | insert | `name`, `is_closed`, `colour`, `is_default`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/delete_change_status.php](/code/files/api/change-management/delete_change_status.php.md) | L30, L43 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L34 |  |
| [api/change-management/get_approvals.php](/code/files/api/change-management/get_approvals.php.md) | L25, L107, L119 |  |
| [api/change-management/get_calendar_changes.php](/code/files/api/change-management/get_calendar_changes.php.md) | L31 |  |
| [api/change-management/get_change_statuses.php](/code/files/api/change-management/get_change_statuses.php.md) | L20 |  |
| [api/change-management/list.php](/code/files/api/change-management/list.php.md) | L40, L87 |  |
| [api/change-management/save_change_status.php](/code/files/api/change-management/save_change_status.php.md) | L59 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L51 |  |
| [api/problem-management/list_linkable_changes.php](/code/files/api/problem-management/list_linkable_changes.php.md) | L32 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2447 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L220 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeSelect L57, apiChangesList L258 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemsGet L241 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystReferenceRegistry L58, analystReferenceRegistry L59, analystReferenceRegistry L60 |  |
| [includes/feature_bingo/cards/changes.php](/code/files/includes/feature_bingo/cards/changes.php.md) | L43 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L198, projectUnapprovedChanges L503 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewChange L231 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::voteCab L576, ChangesService::voteCab L583, ChangesService::loadJoined L746 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolRecentChanges L494 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L291, getWatchtowerData L315, getWatchtowerData L328, getWatchtowerData L349, getWatchtowerData L376 |  |


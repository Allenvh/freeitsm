# manager_grants

Management lines (discussion #62, step 3): who a portal user may see the tickets of, as a MANAGER. One row per line; exclusions always win. See includes/managers.php. uq_manager_grant (db_verify_indexes.php) stops the same line being added twice.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `manager_user_id` | `INT NOT NULL` |  |
| `grant_type` | `VARCHAR(20) NOT NULL` | everyone \| user \| group \| department \| reports |
| `target_id` | `INT NOT NULL DEFAULT 0` | users.id or knowledge_user_groups.id |
| `target_value` | `VARCHAR(150) NOT NULL DEFAULT ''` | department name, or 'all' for reports |
| `is_exclusion` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md):313 |  | delete |  |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md):343 |  | delete |  |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md):355 |  | insert | `manager_user_id`, `grant_type`, `target_id`, `target_value`, `is_exclusion`, `created_by_analyst_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/managers.php](/code/files/api/system/managers.php.md) | managersStatus L48, L98, L113, L128 |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) | maReady L70, maState L108, L224, L349 |  |
| [includes/managers.php](/code/files/includes/managers.php.md) | managersActive L101, managerTeamUserIds L143, managersNotifyNewTicket L415 |  |


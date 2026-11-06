# files_audit

Every action, reads and refusals included. No FKs: it outlives what it names.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `BIGINT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NULL` |  |
| `analyst_name` | `VARCHAR(150) NULL` |  |
| `action` | `VARCHAR(40) NOT NULL` |  |
| `folder_id` | `INT NULL` |  |
| `item_id` | `INT NULL` |  |
| `version_id` | `INT NULL` |  |
| `target_path` | `VARCHAR(1000) NULL` |  |
| `detail` | `TEXT NULL` |  |
| `ip_address` | `VARCHAR(45) NULL` |  |
| `user_agent` | `VARCHAR(255) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/files/audit.php](/code/files/includes/files/audit.php.md):60 | `filesAudit` | insert | `analyst_id`, `analyst_name`, `action`, `folder_id`, `item_id`, `version_id`, `target_path`, `detail`, `ip_address`, `user_agent` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/recent.php](/code/files/api/files/recent.php.md) | L11 |  |


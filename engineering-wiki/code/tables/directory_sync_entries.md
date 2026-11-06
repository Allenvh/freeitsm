# directory_sync_entries

What a run did to each PERSON. "47 updated" is a number; this is the answer to "updated how, and who", which is the only actionable version.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `run_id` | `INT NOT NULL` |  |
| `action` | `VARCHAR(16) NOT NULL` |  |
| `user_id` | `INT NULL` |  |
| `directory_username` | `VARCHAR(255) NULL` |  |
| `display_name` | `VARCHAR(255) NULL` |  |
| `detail` | `VARCHAR(1000) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/directory_sync.php](/code/files/includes/directory_sync.php.md):522 | `dsyncLogEntry` | insert | `run_id`, `action`, `user_id`, `directory_username`, `display_name`, `detail`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/get_directory_sync_log.php](/code/files/api/system/get_directory_sync_log.php.md) | L45, L54 |  |


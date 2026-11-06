# intune_sync_jobs

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `started_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `finished_datetime` | `DATETIME NULL` |  |
| `status` | `VARCHAR(16) NOT NULL DEFAULT 'running'` |  |
| `total` | `INT NOT NULL DEFAULT 0` |  |
| `processed` | `INT NOT NULL DEFAULT 0` |  |
| `message` | `LONGTEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/intune/sync.php](/code/files/api/intune/sync.php.md):44 |  | update | `status`, `message`, `finished_datetime` |  |
| [api/intune/sync.php](/code/files/api/intune/sync.php.md):63 |  | insert | `started_datetime`, `status`, `total`, `processed`, `message` |  |
| [api/intune/sync.php](/code/files/api/intune/sync.php.md):72 |  | update | `status`, `message`, `finished_datetime` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):455 | `intuneRunSync` | update | `total`, `processed`, `status`, `finished_datetime`, `message` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/intune/dashboard_data.php](/code/files/api/intune/dashboard_data.php.md) | L204 |  |
| [api/intune/sync.php](/code/files/api/intune/sync.php.md) | L55 |  |
| [api/intune/sync_status.php](/code/files/api/intune/sync_status.php.md) | L28, L34 |  |


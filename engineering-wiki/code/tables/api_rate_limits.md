# api_rate_limits

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `apikey_id` | `INT NOT NULL` |  |
| `request_count` | `INT NOT NULL DEFAULT 0` |  |
| `window_start` | `DATETIME NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/watchtower/get_dashboard_ext.php](/code/files/api/watchtower/get_dashboard_ext.php.md):92 |  | insert | `apikey_id`, `request_count`, `window_start` |  |
| [api/watchtower/get_dashboard_ext.php](/code/files/api/watchtower/get_dashboard_ext.php.md):106 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/watchtower/get_dashboard_ext.php](/code/files/api/watchtower/get_dashboard_ext.php.md) | L100 |  |


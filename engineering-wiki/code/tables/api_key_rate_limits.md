# api_key_rate_limits

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `api_key_id` | `INT NOT NULL` |  |
| `request_count` | `INT NOT NULL DEFAULT 0` |  |
| `window_start` | `DATETIME NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md):142 | `apiRateLimit` | insert | `api_key_id`, `request_count`, `window_start` |  |
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md):152 | `apiRateLimit` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) | apiRateLimit L148 |  |


# ip_login_bans

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ip_address` | `VARCHAR(45) NOT NULL` |  |
| `attempt_count` | `INT NOT NULL DEFAULT 0` |  |
| `ban_count` | `INT NOT NULL DEFAULT 0` |  |
| `banned_until` | `DATETIME NULL` |  |
| `last_attempt` | `DATETIME NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md) | L330 |  |


# user_password_reset_tokens

Password reset for SELF-SERVICE PORTAL users (GH #134). Separate from `password_reset_tokens`, which is the analyst one and is hard-wired to analyst_id — see the note beside this table in database/freeitsm.sql for why the two are not merged.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT UNSIGNED NOT NULL AUTO_INCREMENT` |  |
| `user_id` | `INT NOT NULL` |  |
| `token_hash` | `CHAR(64) NOT NULL` |  |
| `expires_at` | `DATETIME NOT NULL` |  |
| `used` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md):79 |  | update | `used` |  |
| [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md):88 |  | insert | `user_id`, `token_hash`, `expires_at`, `used`, `created_at` |  |
| [api/self-service/reset_password.php](/code/files/api/self-service/reset_password.php.md):100 |  | update | `used` |  |
| [api/self-service/reset_password.php](/code/files/api/self-service/reset_password.php.md):113 |  | update | `used` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/reset_password.php](/code/files/api/self-service/reset_password.php.md) | L73 |  |


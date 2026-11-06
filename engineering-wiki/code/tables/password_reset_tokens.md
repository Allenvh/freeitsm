# password_reset_tokens

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `token_hash` | `VARCHAR(255) NOT NULL` |  |
| `expires_datetime` | `DATETIME NOT NULL` |  |
| `used` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md):66 |  | update | `used` |  |
| [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md):73 |  | insert | `analyst_id`, `token_hash`, `expires_datetime`, `created_datetime` |  |
| [api/auth/reset_password.php](/code/files/api/auth/reset_password.php.md):75 |  | update | `used` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/auth/reset_password.php](/code/files/api/auth/reset_password.php.md) | L48 |  |


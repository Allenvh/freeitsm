# user_verification_tokens

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT UNSIGNED NOT NULL AUTO_INCREMENT` |  |
| `email` | `VARCHAR(255) NOT NULL` |  |
| `password_hash` | `VARCHAR(255) NOT NULL` |  |
| `display_name` | `VARCHAR(255) NULL` |  |
| `token_hash` | `CHAR(64) NOT NULL` |  |
| `expires_at` | `DATETIME NOT NULL` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/self-service/register.php](/code/files/api/self-service/register.php.md):105 |  | delete |  |  |
| [api/self-service/register.php](/code/files/api/self-service/register.php.md):107 |  | insert | `email`, `password_hash`, `display_name`, `token_hash`, `expires_at`, `created_at` |  |
| [api/self-service/register.php](/code/files/api/self-service/register.php.md):120 |  | delete |  |  |
| [self-service/verify-email.php](/code/files/self-service/verify-email.php.md):75 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [self-service/verify-email.php](/code/files/self-service/verify-email.php.md) | L28 |  |


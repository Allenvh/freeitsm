# api/auth/reset_password.php

> API endpoint - 96 lines

Reset Password

Validates a reset token and updates the analyst's password.

POST JSON: { "token": "...", "new_password": "...", "confirm_password": "..." }

## The endpoint

| | |
|---|---|
| URL | `api/auth/reset_password.php` |
| Protected by | Signed-in analyst (session check) (via includes/csrf.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [password_reset_tokens](/code/tables/password_reset_tokens.md) |  |
| 71 |  | **update** [analysts](/code/tables/analysts.md) (`password_hash`, `password_changed_datetime`, `failed_login_count`, `locked_until`) |  |
| 75 |  | **update** [password_reset_tokens](/code/tables/password_reset_tokens.md) (`used`) |  |
| 84 |  | **insert** [system_logs](/code/tables/system_logs.md) (`log_type`, `analyst_id`, `details`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/db.php](/code/files/includes/db.php.md), [includes/request_guard.php](/code/files/includes/request_guard.php.md)

**Calls:** [dbConnectionOptions()](/code/functions/dbConnectionOptions.md)


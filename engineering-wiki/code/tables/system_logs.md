# system_logs

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `log_type` | `VARCHAR(50) NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `analyst_id` | `INT NULL` |  |
| `details` | `LONGTEXT NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/auth/reset_password.php](/code/files/api/auth/reset_password.php.md):84 |  | insert | `log_type`, `analyst_id`, `details`, `created_datetime` |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1526 | `logEmailImport` | insert | `log_type`, `analyst_id`, `details`, `created_datetime` |  |
| [api/tickets/unlink_user_signin.php](/code/files/api/tickets/unlink_user_signin.php.md):76 |  | insert | `log_type`, `analyst_id`, `details`, `created_datetime` |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md):709 | `analystLcLog` | insert | `log_type`, `analyst_id`, `details`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/reporting/get_system_logs.php](/code/files/api/reporting/get_system_logs.php.md) | L27, L58 |  |


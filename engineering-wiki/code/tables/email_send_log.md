# email_send_log

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `mailbox_id` | `INT NULL` |  |
| `ticket_id` | `INT NULL` |  |
| `route` | `VARCHAR(30) NOT NULL` |  |
| `provider` | `VARCHAR(20) NULL` |  |
| `auth_mode` | `VARCHAR(20) NULL` |  |
| `to_address` | `VARCHAR(255) NOT NULL` |  |
| `subject` | `VARCHAR(500) NULL` |  |
| `status` | `VARCHAR(10) NOT NULL` |  |
| `error_message` | `TEXT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/email_log.php](/code/files/includes/email_log.php.md):91 | `emailLogRecord` | insert | `mailbox_id`, `ticket_id`, `route`, `provider`, `auth_mode`, `to_address`, `subject`, `status`, `error_message`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_mailbox_outbound.php](/code/files/api/tickets/get_mailbox_outbound.php.md) | L61, L65, L84 |  |


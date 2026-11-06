# mailbox_activity_log

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `mailbox_id` | `INT NOT NULL` |  |
| `action` | `VARCHAR(20) NOT NULL` |  |
| `from_address` | `VARCHAR(255) NOT NULL` |  |
| `from_name` | `VARCHAR(255) NULL` |  |
| `subject` | `VARCHAR(500) NULL` |  |
| `reason` | `VARCHAR(255) NULL` |  |
| `ticket_id` | `INT NULL` |  |
| `processing_log` | `TEXT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1595 | `logMailboxActivity` | insert | `mailbox_id`, `action`, `from_address`, `from_name`, `subject`, `reason`, `ticket_id`, `processing_log`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_mailbox_activity.php](/code/files/api/tickets/get_mailbox_activity.php.md) | L44, L49 |  |
| [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) | oversizeReplySkipReason L335 |  |


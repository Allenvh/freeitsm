# mailbox_email_whitelist

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `mailbox_id` | `INT NOT NULL` |  |
| `entry_type` | `VARCHAR(10) NOT NULL` |  |
| `entry_value` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/save_mailbox_whitelist.php](/code/files/api/tickets/save_mailbox_whitelist.php.md):38 |  | delete |  |  |
| [api/tickets/save_mailbox_whitelist.php](/code/files/api/tickets/save_mailbox_whitelist.php.md):43 |  | insert | `mailbox_id`, `entry_type`, `entry_value` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) | loadWhitelist L1542 |  |
| [api/tickets/get_mailbox_whitelist.php](/code/files/api/tickets/get_mailbox_whitelist.php.md) | L28 |  |


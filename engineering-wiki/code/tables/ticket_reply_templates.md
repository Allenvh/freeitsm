# ticket_reply_templates

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `body` | `LONGTEXT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_reply_template.php](/code/files/api/tickets/delete_reply_template.php.md):45 |  | delete |  |  |
| [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md):95 |  | update | `name`, `body`, `analyst_id`, `tenant_id`, `is_active`, `display_order`, `updated_datetime` |  |
| [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md):103 |  | insert | `name`, `body`, `analyst_id`, `tenant_id`, `is_active`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/reply_templates.php](/code/files/includes/reply_templates.php.md) | replyTemplatesVisibleTo L118, replyTemplateWriteScope L149 |  |


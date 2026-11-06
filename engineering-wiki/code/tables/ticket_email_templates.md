# ticket_email_templates

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `event_trigger` | `VARCHAR(50) NOT NULL` |  |
| `subject_template` | `VARCHAR(500) NOT NULL` |  |
| `body_template` | `LONGTEXT NOT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_email_template.php](/code/files/api/tickets/delete_email_template.php.md):36 |  | delete |  |  |
| [api/tickets/save_email_template.php](/code/files/api/tickets/save_email_template.php.md):79 |  | update | `name`, `event_trigger`, `subject_template`, `body_template`, `is_active`, `display_order`, `updated_datetime` |  |
| [api/tickets/save_email_template.php](/code/files/api/tickets/save_email_template.php.md):86 |  | insert | `name`, `event_trigger`, `subject_template`, `body_template`, `is_active`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_close_message_config.php](/code/files/api/tickets/get_close_message_config.php.md) | L40 |  |
| [api/tickets/get_email_templates.php](/code/files/api/tickets/get_email_templates.php.md) | L22 |  |
| [includes/csat.php](/code/files/includes/csat.php.md) | sendCsatSurvey L336 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | templateSelectForRecipient L269 |  |


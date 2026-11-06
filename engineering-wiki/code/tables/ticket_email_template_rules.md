# ticket_email_template_rules

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `template_id` | `INT NOT NULL` |  |
| `match_type` | `VARCHAR(10) NOT NULL` |  |
| `match_value` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_email_template.php](/code/files/api/tickets/delete_email_template.php.md):34 |  | delete |  |  |
| [api/tickets/save_email_template.php](/code/files/api/tickets/save_email_template.php.md):106 |  | delete |  |  |
| [api/tickets/save_email_template.php](/code/files/api/tickets/save_email_template.php.md):108 |  | insert | `template_id`, `match_type`, `match_value` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/template_email.php](/code/files/includes/template_email.php.md) | templateRulesByTemplate L327 |  |


# messaging_templates

Pre-approved provider message templates (replying after the WhatsApp 24h window). FreeITSM stores the definition; the template is created/approved at the provider. provider_ref = Twilio Content SID or Meta template name. language used by Meta.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `provider` | `VARCHAR(20) NOT NULL DEFAULT 'twilio'` |  |
| `language` | `VARCHAR(20) NOT NULL DEFAULT 'en'` |  |
| `provider_ref` | `VARCHAR(255) NOT NULL` |  |
| `body` | `LONGTEXT NOT NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/messaging/delete_template.php](/code/files/api/messaging/delete_template.php.md):28 |  | delete |  |  |
| [api/messaging/save_template.php](/code/files/api/messaging/save_template.php.md):42 |  | update | `name`, `provider`, `language`, `provider_ref`, `body`, `is_active` |  |
| [api/messaging/save_template.php](/code/files/api/messaging/save_template.php.md):46 |  | insert | `name`, `provider`, `language`, `provider_ref`, `body`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/messaging/get_templates.php](/code/files/api/messaging/get_templates.php.md) | L27 |  |
| [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md) | L57 |  |


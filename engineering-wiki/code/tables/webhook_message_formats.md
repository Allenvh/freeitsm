# webhook_message_formats

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `format_key` | `VARCHAR(40) NOT NULL` |  |
| `label` | `VARCHAR(100) NOT NULL` |  |
| `body_template` | `TEXT NOT NULL` |  |
| `url_pattern` | `VARCHAR(255) NULL` |  |
| `markdown_hint` | `VARCHAR(255) NULL` |  |
| `is_builtin` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3210 |  | insert | `format_key`, `label`, `body_template`, `url_pattern`, `markdown_hint`, `is_builtin`, `display_order` |  |
| [api/workflow/delete_format.php](/code/files/api/workflow/delete_format.php.md):66 |  | delete |  |  |
| [api/workflow/save_format.php](/code/files/api/workflow/save_format.php.md):97 |  | update | `format_key`, `label`, `body_template`, `url_pattern`, `markdown_hint`, `is_active` |  |
| [api/workflow/save_format.php](/code/files/api/workflow/save_format.php.md):103 |  | insert | `format_key`, `label`, `body_template`, `url_pattern`, `markdown_hint`, `is_builtin`, `is_active`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/workflow/delete_format.php](/code/files/api/workflow/delete_format.php.md) | L35 |  |
| [api/workflow/formats.php](/code/files/api/workflow/formats.php.md) | L25 |  |
| [api/workflow/save_format.php](/code/files/api/workflow/save_format.php.md) | L80, L91 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::webhookFormats L581 |  |


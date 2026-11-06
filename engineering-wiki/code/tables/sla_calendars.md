# sla_calendars

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `timezone` | `VARCHAR(50) NOT NULL DEFAULT 'Europe/London'` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1875 |  | insert | `name`, `timezone`, `is_default` |  |
| [api/tickets/delete_sla_calendar.php](/code/files/api/tickets/delete_sla_calendar.php.md):39 |  | delete |  |  |
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):86 |  | update | `is_default` |  |
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):90 |  | update | `name`, `timezone`, `is_default` |  |
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):93 |  | insert | `name`, `timezone`, `is_default` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L1873 |  |
| [api/tickets/get_sla_calendar.php](/code/files/api/tickets/get_sla_calendar.php.md) | L24 |  |
| [api/tickets/get_sla_settings.php](/code/files/api/tickets/get_sla_settings.php.md) | L49 |  |
| [api/tickets/save_priority_sla.php](/code/files/api/tickets/save_priority_sla.php.md) | L38 |  |
| [api/webchat/get_widgets.php](/code/files/api/webchat/get_widgets.php.md) | L45 |  |
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md) | L76 |  |
| [includes/feature_bingo/cards/tickets-core.php](/code/files/includes/feature_bingo/cards/tickets-core.php.md) | L359 |  |
| [includes/sla.php](/code/files/includes/sla.php.md) | sla_get_state L97, sla_load_calendar L367 |  |


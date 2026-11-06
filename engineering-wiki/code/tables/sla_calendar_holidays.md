# sla_calendar_holidays

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `calendar_id` | `INT NOT NULL` |  |
| `holiday_date` | `DATE NOT NULL` |  |
| `name` | `VARCHAR(100) NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):108 |  | delete |  |  |
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):110 |  | insert | `calendar_id`, `holiday_date`, `name` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_sla_calendar.php](/code/files/api/tickets/get_sla_calendar.php.md) | L40 |  |
| [api/tickets/get_sla_settings.php](/code/files/api/tickets/get_sla_settings.php.md) | L63 |  |
| [includes/sla.php](/code/files/includes/sla.php.md) | sla_load_calendar L376 |  |


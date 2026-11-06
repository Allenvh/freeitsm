# sla_calendar_hours

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `calendar_id` | `INT NOT NULL` |  |
| `weekday` | `TINYINT NOT NULL` |  |
| `start_time` | `TIME NOT NULL` |  |
| `end_time` | `TIME NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1878 |  | insert | `calendar_id`, `weekday`, `start_time`, `end_time` |  |
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):99 |  | delete |  |  |
| [api/tickets/save_sla_calendar.php](/code/files/api/tickets/save_sla_calendar.php.md):101 |  | insert | `calendar_id`, `weekday`, `start_time`, `end_time` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_sla_calendar.php](/code/files/api/tickets/get_sla_calendar.php.md) | L30 |  |
| [api/tickets/get_sla_settings.php](/code/files/api/tickets/get_sla_settings.php.md) | L56 |  |
| [includes/feature_bingo/cards/tickets-core.php](/code/files/includes/feature_bingo/cards/tickets-core.php.md) | L359 |  |
| [includes/sla.php](/code/files/includes/sla.php.md) | sla_load_calendar L372 |  |


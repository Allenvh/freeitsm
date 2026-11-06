# ticket_rota_entries

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `rota_date` | `DATE NOT NULL` |  |
| `shift_id` | `INT NOT NULL` |  |
| `location_id` | `INT NULL` |  |
| `is_on_call` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2389 |  | update | `location_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2403 |  | update | `location_id` |  |
| [api/tickets/clear_rota_cells.php](/code/files/api/tickets/clear_rota_cells.php.md):82 |  | delete |  |  |
| [api/tickets/delete_rota_entry.php](/code/files/api/tickets/delete_rota_entry.php.md):28 |  | delete |  |  |
| [api/tickets/paste_rota_cells.php](/code/files/api/tickets/paste_rota_cells.php.md):148 |  | insert | `analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime` |  |
| [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md):100 |  | delete |  |  |
| [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md):108 |  | delete |  |  |
| [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md):119 |  | insert | `analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime` |  |
| [api/tickets/paste_rota_week.php](/code/files/api/tickets/paste_rota_week.php.md):91 |  | delete |  |  |
| [api/tickets/paste_rota_week.php](/code/files/api/tickets/paste_rota_week.php.md):99 |  | insert | `analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime` |  |
| [api/tickets/save_rota_entry.php](/code/files/api/tickets/save_rota_entry.php.md):46 |  | update | `shift_id`, `location_id`, `is_on_call`, `updated_datetime` |  |
| [api/tickets/save_rota_entry.php](/code/files/api/tickets/save_rota_entry.php.md):53 |  | insert | `analyst_id`, `rota_date`, `shift_id`, `location_id`, `is_on_call`, `created_datetime`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2372, L2418 |  |
| [api/tickets/delete_rota_location.php](/code/files/api/tickets/delete_rota_location.php.md) | L35 |  |
| [api/tickets/delete_rota_shift.php](/code/files/api/tickets/delete_rota_shift.php.md) | L31 |  |
| [api/tickets/get_rota.php](/code/files/api/tickets/get_rota.php.md) | L65 |  |
| [api/tickets/paste_rota_cells.php](/code/files/api/tickets/paste_rota_cells.php.md) | L135 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L221 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolOnCall L532 |  |


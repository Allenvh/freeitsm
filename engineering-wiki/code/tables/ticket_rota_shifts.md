# ticket_rota_shifts

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `start_time` | `TIME NOT NULL` |  |
| `end_time` | `TIME NOT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_rota_shift.php](/code/files/api/tickets/delete_rota_shift.php.md):41 |  | delete |  |  |
| [api/tickets/save_rota_shift.php](/code/files/api/tickets/save_rota_shift.php.md):47 |  | update | `name`, `start_time`, `end_time`, `display_order`, `is_active` |  |
| [api/tickets/save_rota_shift.php](/code/files/api/tickets/save_rota_shift.php.md):53 |  | insert | `name`, `start_time`, `end_time`, `display_order`, `is_active`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_rota.php](/code/files/api/tickets/get_rota.php.md) | L46, L65 |  |
| [api/tickets/get_rota_shifts.php](/code/files/api/tickets/get_rota_shifts.php.md) | L20 |  |
| [api/tickets/paste_rota_cells.php](/code/files/api/tickets/paste_rota_cells.php.md) | L75 |  |
| [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md) | L87 |  |
| [api/tickets/paste_rota_week.php](/code/files/api/tickets/paste_rota_week.php.md) | L84 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L221 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolOnCall L532 |  |


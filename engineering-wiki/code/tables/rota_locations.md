# rota_locations

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2362 |  | insert | `name`, `colour`, `is_default`, `display_order` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2372 |  | insert | `name`, `display_order` |  |
| [api/tickets/delete_rota_location.php](/code/files/api/tickets/delete_rota_location.php.md):43 |  | delete |  |  |
| [api/tickets/save_rota_location.php](/code/files/api/tickets/save_rota_location.php.md):40 |  | update | `is_default` |  |
| [api/tickets/save_rota_location.php](/code/files/api/tickets/save_rota_location.php.md):46 |  | update | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/tickets/save_rota_location.php](/code/files/api/tickets/save_rota_location.php.md):52 |  | insert | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/tickets/save_rota_location.php](/code/files/api/tickets/save_rota_location.php.md):60 |  | update | `is_default` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2360, L2372, L2389, L2403 |  |
| [api/tickets/delete_rota_location.php](/code/files/api/tickets/delete_rota_location.php.md) | L30 |  |
| [api/tickets/get_rota.php](/code/files/api/tickets/get_rota.php.md) | L52, L65 |  |
| [api/tickets/get_rota_locations.php](/code/files/api/tickets/get_rota_locations.php.md) | L20 |  |
| [api/tickets/paste_rota_cells.php](/code/files/api/tickets/paste_rota_cells.php.md) | L82, L89 |  |
| [api/tickets/paste_rota_line.php](/code/files/api/tickets/paste_rota_line.php.md) | L89 |  |
| [api/tickets/paste_rota_week.php](/code/files/api/tickets/paste_rota_week.php.md) | L86 |  |
| [api/tickets/save_rota_entry.php](/code/files/api/tickets/save_rota_entry.php.md) | L41 |  |
| [api/tickets/save_rota_location.php](/code/files/api/tickets/save_rota_location.php.md) | L58 |  |


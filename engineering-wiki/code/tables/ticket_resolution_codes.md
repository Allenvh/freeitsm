# ticket_resolution_codes

How a ticket ENDED, not what it was about. Flat on purpose: the moment this grows a hierarchy it is a second category tree and the two will drift.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3427 |  | insert | `name`, `description`, `display_order` |  |
| [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md):61 |  | delete |  |  |
| [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md):78 |  | update | `name`, `description`, `is_active`, `display_order` |  |
| [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md):83 |  | insert | `name`, `description`, `is_active`, `display_order`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3425 |  |
| [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md) | L38 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L343, getCategoricalWithSeries L407 |  |
| [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md) | L54, L64 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L31 |  |
| [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md) | ticketResolutionCodesResolved L325, ticketResolutionCodeName L360 |  |


# ticket_origins

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy: NULL = global default origin; set = a company's own. (Config meaning of tenant_id — see ticket_types.) Existing rows stay NULL, so a single-company install is unaffected. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):859 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1475 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1484 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1495 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1506 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |
| [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md):73 |  | delete |  |  |
| [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md):90 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md):95 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L857, L1472, L1481, L1492, L1503 |  |
| [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md) | L40, L72 |  |
| [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md) | L131 |  |
| [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md) | L75, L86 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L349, getCategoricalWithSeries L397 |  |
| [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md) | L144 |  |
| [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md) | L53, L54, L69 |  |
| [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md) | L65 |  |
| [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md) | L42 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38 |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) | getChannelOriginId L982 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L30 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::loadTicket L1100 |  |


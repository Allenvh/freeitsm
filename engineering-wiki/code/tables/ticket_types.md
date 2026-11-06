# ticket_types

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy: NULL = a global default type (shared by every company); set = a type that company added for itself. Existing rows stay NULL, so a single-company install is unaffected (all types are global). NB this is the *config* meaning of tenant_id (NULL = global default) — different from scoped data tables like `tickets` where NULL means "unrouted". |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):845 |  | insert | `name`, `description`, `is_active`, `display_order`, `tenant_id` |  |
| [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md):69 |  | delete |  |  |
| [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md):94 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md):97 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md) | L83 |  |
| [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md) | L66 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L843 |  |
| [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md) | L41, L68 |  |
| [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md) | L87, L98 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L311, getCategoricalWithSeries L388 |  |
| [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md) | L109 |  |
| [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md) | L56, L57, L73 |  |
| [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md) | L53 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L27 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::loadTicket L1100 |  |
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md) | TicketNumbering::typeCode L265 |  |


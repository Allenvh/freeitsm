# ticket_categories

Ticket classification (#1540). The tree behind BOTH the category a ticket is raised with and the one it is closed with — the same list, asked twice.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `parent_id` | `INT NULL` | Sub-categories. NULL = a root. Depth capped at 3 in the API, not here. ⚠️ The ticket stores the LEAF id ONLY — there is deliberately no parent_category_id on `tickets`. Ancestors are DERIVED for roll-up reporting; storing both would let them disagree. |
| `ticket_type_id` | `INT NULL` | Optional link to a ticket type. 🔑 ONLY MEANINGFUL ON A ROOT — a child inherits its root's type and the API refuses to set this on a child. |
| `is_portal_visible` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` | Retire rather than delete, so closed tickets keep their label. |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy: NULL = a global default; set = a company's own. (Config meaning of tenant_id — see ticket_types.) |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md):95 |  | delete |  |  |
| [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md):157 |  | update | `name`, `description`, `parent_id`, `ticket_type_id`, `is_portal_visible`, `is_active`, `display_order` |  |
| [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md):166 |  | insert | `name`, `description`, `parent_id`, `ticket_type_id`, `is_portal_visible`, `is_active`, `display_order`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md) | L53, L67 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L334, getCategoricalWithSeries L402 |  |
| [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md) | L76, L115, L130 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L23, rpTicketsList L261 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::categoryEffectiveTypeId L1264 |  |
| [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md) | ticketCategoriesResolved L74, ticketCategoryPathLabel L210, ticketCategoryDepthUnder L239, ticketCategorySubtreeDepth L266, ticketCategoryWouldCycle L296 |  |


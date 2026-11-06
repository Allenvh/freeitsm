# departments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `default_sensitivity` | `VARCHAR(20) NOT NULL DEFAULT 'normal'` | 'confidential' = every ticket in, or moved into, this department becomes confidential (discussion #62). Only ever raises; see ticket_sensitivity.php. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/delete_department.php](/code/files/api/tickets/delete_department.php.md):29 |  | delete |  |  |
| [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md):37 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md):42 |  | insert | `name`, `description`, `display_order`, `is_active` |  |
| [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md):59 |  | update | `default_sensitivity` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_tickets.php](/code/files/api/assets/get_asset_tickets.php.md) | L50 |  |
| [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md) | L45 |  |
| [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md) | L58 |  |
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L80 |  |
| [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md) | L101 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L48 |  |
| [api/self-service/get_tickets.php](/code/files/api/self-service/get_tickets.php.md) | L30 |  |
| [api/system/assign_ticket_department.php](/code/files/api/system/assign_ticket_department.php.md) | L40 |  |
| [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md) | L329, L356, L388, L412, L423 |  |
| [api/system/get_orphaned_tickets.php](/code/files/api/system/get_orphaned_tickets.php.md) | L27, L61 |  |
| [api/tickets/get_departments.php](/code/files/api/tickets/get_departments.php.md) | L24 |  |
| [api/tickets/get_my_departments.php](/code/files/api/tickets/get_my_departments.php.md) | L35, L58 |  |
| [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md) | L45 |  |
| [api/tickets/get_sla_notification_rules.php](/code/files/api/tickets/get_sla_notification_rules.php.md) | L24, L42 |  |
| [api/tickets/get_team_departments.php](/code/files/api/tickets/get_team_departments.php.md) | L28 |  |
| [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md) | L119, L135, L173, L189 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L309, getCategoricalWithSeries L385 |  |
| [api/tickets/get_user_tickets.php](/code/files/api/tickets/get_user_tickets.php.md) | L31 |  |
| [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md) | L56 |  |
| [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) | apiDepartmentsList L122 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L26 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::loadTicket L1100 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | buildTicketMergeData L350 |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) | ticketSensitivityReady L53, ticketSensitivityApplyDefaults L118 |  |


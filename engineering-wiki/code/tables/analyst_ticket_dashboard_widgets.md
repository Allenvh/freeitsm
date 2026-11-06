# analyst_ticket_dashboard_widgets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `widget_id` | `INT NOT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `status_filter` | `VARCHAR(50) NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/add_ticket_dashboard_widget.php](/code/files/api/tickets/add_ticket_dashboard_widget.php.md):35 |  | insert | `analyst_id`, `widget_id`, `sort_order` |  |
| [api/tickets/delete_ticket_dashboard_widget.php](/code/files/api/tickets/delete_ticket_dashboard_widget.php.md):30 |  | delete |  |  |
| [api/tickets/remove_ticket_dashboard_widget.php](/code/files/api/tickets/remove_ticket_dashboard_widget.php.md):31 |  | delete |  |  |
| [api/tickets/reorder_ticket_dashboard_widgets.php](/code/files/api/tickets/reorder_ticket_dashboard_widgets.php.md):31 |  | update | `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/add_ticket_dashboard_widget.php](/code/files/api/tickets/add_ticket_dashboard_widget.php.md) | L31 |  |
| [api/tickets/get_ticket_dashboard.php](/code/files/api/tickets/get_ticket_dashboard.php.md) | L22 |  |


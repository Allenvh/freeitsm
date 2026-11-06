# ticket_dashboard_widgets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `chart_type` | `VARCHAR(20) NOT NULL DEFAULT 'bar'` |  |
| `aggregate_property` | `VARCHAR(50) NOT NULL` |  |
| `series_property` | `VARCHAR(20) NULL DEFAULT NULL` |  |
| `is_status_filterable` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `default_status` | `VARCHAR(50) NULL` |  |
| `date_range` | `VARCHAR(20) NULL DEFAULT NULL` |  |
| `department_filter` | `JSON NULL` |  |
| `time_grouping` | `VARCHAR(10) NULL DEFAULT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):727 |  | update | `aggregate_property`, `time_grouping`, `date_range` |  |
| [api/tickets/delete_ticket_dashboard_widget.php](/code/files/api/tickets/delete_ticket_dashboard_widget.php.md):33 |  | update | `is_active` |  |
| [api/tickets/save_ticket_dashboard_widget.php](/code/files/api/tickets/save_ticket_dashboard_widget.php.md):97 |  | update | `title`, `description`, `chart_type`, `aggregate_property`, `series_property`, `is_status_filterable`, `date_range`, `department_filter`, `time_grouping` |  |
| [api/tickets/save_ticket_dashboard_widget.php](/code/files/api/tickets/save_ticket_dashboard_widget.php.md):109 |  | insert | `title`, `description`, `chart_type`, `aggregate_property`, `series_property`, `is_status_filterable`, `date_range`, `department_filter`, `time_grouping`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L716 |  |
| [api/tickets/get_ticket_dashboard.php](/code/files/api/tickets/get_ticket_dashboard.php.md) | L22 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | L33 |  |
| [api/tickets/get_ticket_widget_library.php](/code/files/api/tickets/get_ticket_widget_library.php.md) | L20 |  |
| [api/tickets/save_ticket_dashboard_widget.php](/code/files/api/tickets/save_ticket_dashboard_widget.php.md) | L106, L119 |  |


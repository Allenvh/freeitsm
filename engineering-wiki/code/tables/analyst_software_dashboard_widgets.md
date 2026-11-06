# analyst_software_dashboard_widgets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `widget_id` | `INT NOT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/software/add_software_dashboard_widget.php](/code/files/api/software/add_software_dashboard_widget.php.md):36 |  | insert | `analyst_id`, `widget_id`, `sort_order` |  |
| [api/software/delete_software_dashboard_widget.php](/code/files/api/software/delete_software_dashboard_widget.php.md):30 |  | delete |  |  |
| [api/software/remove_software_dashboard_widget.php](/code/files/api/software/remove_software_dashboard_widget.php.md):31 |  | delete |  |  |
| [api/software/reorder_software_dashboard_widgets.php](/code/files/api/software/reorder_software_dashboard_widgets.php.md):31 |  | update | `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/software/add_software_dashboard_widget.php](/code/files/api/software/add_software_dashboard_widget.php.md) | L32 |  |
| [api/software/get_software_dashboard.php](/code/files/api/software/get_software_dashboard.php.md) | L22 |  |


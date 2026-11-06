# analyst_dashboard_widgets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `widget_id` | `INT NOT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `status_filter_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/add_dashboard_widget.php](/code/files/api/assets/add_dashboard_widget.php.md):37 |  | insert | `analyst_id`, `widget_id`, `sort_order` |  |
| [api/assets/delete_dashboard_widget.php](/code/files/api/assets/delete_dashboard_widget.php.md):31 |  | delete |  |  |
| [api/assets/remove_dashboard_widget.php](/code/files/api/assets/remove_dashboard_widget.php.md):32 |  | delete |  |  |
| [api/assets/reorder_dashboard_widgets.php](/code/files/api/assets/reorder_dashboard_widgets.php.md):32 |  | update | `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/add_dashboard_widget.php](/code/files/api/assets/add_dashboard_widget.php.md) | L33 |  |
| [api/assets/get_dashboard.php](/code/files/api/assets/get_dashboard.php.md) | L22 |  |


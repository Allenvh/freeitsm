# asset_dashboard_widgets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `chart_type` | `VARCHAR(20) NOT NULL DEFAULT 'bar'` |  |
| `aggregate_property` | `VARCHAR(50) NOT NULL` |  |
| `is_status_filterable` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `default_status_id` | `INT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/delete_dashboard_widget.php](/code/files/api/assets/delete_dashboard_widget.php.md):34 |  | update | `is_active` |  |
| [api/assets/save_dashboard_widget.php](/code/files/api/assets/save_dashboard_widget.php.md):59 |  | update | `title`, `description`, `chart_type`, `aggregate_property`, `is_status_filterable`, `default_status_id` |  |
| [api/assets/save_dashboard_widget.php](/code/files/api/assets/save_dashboard_widget.php.md):68 |  | insert | `title`, `description`, `chart_type`, `aggregate_property`, `is_status_filterable`, `default_status_id`, `display_order` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):689 |  | insert | `id`, `title`, `description`, `chart_type`, `aggregate_property`, `is_status_filterable`, `default_status_id`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_dashboard.php](/code/files/api/assets/get_dashboard.php.md) | L22 |  |
| [api/assets/get_widget_data.php](/code/files/api/assets/get_widget_data.php.md) | L31 |  |
| [api/assets/get_widget_library.php](/code/files/api/assets/get_widget_library.php.md) | L20 |  |
| [api/assets/save_dashboard_widget.php](/code/files/api/assets/save_dashboard_widget.php.md) | L65, L75 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L687 |  |


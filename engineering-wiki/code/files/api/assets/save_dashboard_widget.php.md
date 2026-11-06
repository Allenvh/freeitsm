# api/assets/save_dashboard_widget.php

> API endpoint - 84 lines

API Endpoint: Create or update a dashboard widget definition POST: { id?, title, description, chart_type, aggregate_property, is_status_filterable, default_status_id? }

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_dashboard_widget.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | **update** [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) (`title`, `description`, `chart_type`, `aggregate_property`, `is_status_filterable`, `default_status_id`) |  |
| 65 |  | read [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) |  |
| 68 |  | **insert** [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) (`title`, `description`, `chart_type`, `aggregate_property`, `is_status_filterable`, `default_status_id`, `display_order`) |  |
| 75 |  | read [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


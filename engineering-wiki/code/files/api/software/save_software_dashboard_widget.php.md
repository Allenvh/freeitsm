# api/software/save_software_dashboard_widget.php

> API endpoint - 87 lines

API Endpoint: Create or update a software dashboard widget definition POST: { id?, title, description, chart_type, aggregate_property, app_id?, exclude_system_components }

## The endpoint

| | |
|---|---|
| URL | `api/software/save_software_dashboard_widget.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | **update** [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) (`title`, `description`, `chart_type`, `aggregate_property`, `app_id`, `exclude_system_components`) |  |
| 65 |  | read [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) |  |
| 68 |  | **insert** [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md) (`title`, `description`, `chart_type`, `aggregate_property`, `app_id`, `exclude_system_components`, `display_order`) |  |
| 75 |  | read [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


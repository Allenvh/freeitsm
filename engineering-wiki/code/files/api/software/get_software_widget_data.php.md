# api/software/get_software_widget_data.php

> API endpoint - 109 lines

API Endpoint: Get aggregated chart data for a software dashboard widget Params: widget_id (required)

## The endpoint

| | |
|---|---|
| URL | `api/software/get_software_widget_data.php` |
| Protected by | Module access: software (requireModuleAccessJson) |
| Reads | `GET widget_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 51 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 68 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 87 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


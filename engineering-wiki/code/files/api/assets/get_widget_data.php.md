# api/assets/get_widget_data.php

> API endpoint - 132 lines

API Endpoint: Get aggregated data for a dashboard widget Params: widget_id (required), status_id (optional filter)

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_widget_data.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET widget_id`, `GET status_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) |  |
| 61 |  | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md) |  |
| 68 |  | read [assets](/code/tables/assets.md), read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 75 |  | read [assets](/code/tables/assets.md) |  |
| 112 |  | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


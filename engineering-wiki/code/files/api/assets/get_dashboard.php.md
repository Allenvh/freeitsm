# api/assets/get_dashboard.php

> API endpoint - 37 lines

API Endpoint: Get analyst's saved dashboard widgets

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_dashboard.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [analyst_dashboard_widgets](/code/tables/analyst_dashboard_widgets.md), read [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


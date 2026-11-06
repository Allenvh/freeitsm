# api/assets/delete_dashboard_widget.php

> API endpoint - 41 lines

API Endpoint: Soft-delete a dashboard widget POST: { id }

## The endpoint

| | |
|---|---|
| URL | `api/assets/delete_dashboard_widget.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **delete** [analyst_dashboard_widgets](/code/tables/analyst_dashboard_widgets.md) |  |
| 34 |  | **update** [asset_dashboard_widgets](/code/tables/asset_dashboard_widgets.md) (`is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


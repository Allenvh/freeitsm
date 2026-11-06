# api/assets/reorder_dashboard_widgets.php

> API endpoint - 43 lines

API Endpoint: Reorder analyst's dashboard widgets POST: { order: [widget_id, widget_id, ...] }

## The endpoint

| | |
|---|---|
| URL | `api/assets/reorder_dashboard_widgets.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | **update** [analyst_dashboard_widgets](/code/tables/analyst_dashboard_widgets.md) (`sort_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/add_dashboard_widget.php

> API endpoint - 50 lines

API Endpoint: Add a widget to analyst's dashboard POST: { widget_id: int }

## The endpoint

| | |
|---|---|
| URL | `api/assets/add_dashboard_widget.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [analyst_dashboard_widgets](/code/tables/analyst_dashboard_widgets.md) |  |
| 37 |  | **insert** [analyst_dashboard_widgets](/code/tables/analyst_dashboard_widgets.md) (`analyst_id`, `widget_id`, `sort_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


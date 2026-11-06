# api/software/add_software_dashboard_widget.php

> API endpoint - 49 lines

API Endpoint: Add a widget to analyst's software dashboard POST: { widget_id: int }

## The endpoint

| | |
|---|---|
| URL | `api/software/add_software_dashboard_widget.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md) |  |
| 36 |  | **insert** [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md) (`analyst_id`, `widget_id`, `sort_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


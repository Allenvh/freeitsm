# api/software/reorder_software_dashboard_widgets.php

> API endpoint - 42 lines

API Endpoint: Reorder analyst's software dashboard widgets POST: { order: [widget_id, widget_id, ...] }

## The endpoint

| | |
|---|---|
| URL | `api/software/reorder_software_dashboard_widgets.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **update** [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md) (`sort_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


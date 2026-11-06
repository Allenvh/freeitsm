# api/software/remove_software_dashboard_widget.php

> API endpoint - 39 lines

API Endpoint: Remove a widget from analyst's software dashboard POST: { widget_id: int }

## The endpoint

| | |
|---|---|
| URL | `api/software/remove_software_dashboard_widget.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **delete** [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


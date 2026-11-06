# api/software/get_software_dashboard.php

> API endpoint - 39 lines

API Endpoint: Get analyst's saved software dashboard widgets

## The endpoint

| | |
|---|---|
| URL | `api/software/get_software_dashboard.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [analyst_software_dashboard_widgets](/code/tables/analyst_software_dashboard_widgets.md), read [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


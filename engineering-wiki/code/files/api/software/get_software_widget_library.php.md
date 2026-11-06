# api/software/get_software_widget_library.php

> API endpoint - 34 lines

API Endpoint: Get all active software dashboard widgets from the library

## The endpoint

| | |
|---|---|
| URL | `api/software/get_software_widget_library.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [software_dashboard_widgets](/code/tables/software_dashboard_widgets.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


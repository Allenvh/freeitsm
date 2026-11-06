# api/software/get_software_drilldown.php

> API endpoint - 77 lines

API Endpoint: Get drill-down data for a software dashboard chart click Params: widget_id (required), plus one of: version+app_id, app_id alone, or publisher

## The endpoint

| | |
|---|---|
| URL | `api/software/get_software_drilldown.php` |
| Protected by | Module access: software (requireModuleAccessJson) |
| Reads | `GET widget_id`, `GET app_id`, `GET version`, `GET publisher` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 46 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [assets](/code/tables/assets.md) |  |
| 59 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/software/get_apps.php

> API endpoint - 78 lines

API Endpoint: Get software applications list Returns all applications with publisher and install counts

## The endpoint

| | |
|---|---|
| URL | `api/software/get_apps.php` |
| Protected by | Module access: software (requireModuleAccessJson) |
| Reads | `GET filter` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/software/get_app_machines.php

> API endpoint - 53 lines

API Endpoint: Get machines with a specific software application Returns hostname, version, install date, and last seen for each machine

## The endpoint

| | |
|---|---|
| URL | `api/software/get_app_machines.php` |
| Protected by | Module access: software (requireModuleAccessJson) |
| Reads | `GET app_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/get_asset_software.php

> API endpoint - 61 lines

API Endpoint: Get software installed on a specific asset Returns list of applications with version, install date, and last seen

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_software.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


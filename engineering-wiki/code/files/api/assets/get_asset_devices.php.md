# api/assets/get_asset_devices.php

> API endpoint - 48 lines

API Endpoint: Get devices for an asset GET: ?asset_id=123

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_devices.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [asset_devices](/code/tables/asset_devices.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


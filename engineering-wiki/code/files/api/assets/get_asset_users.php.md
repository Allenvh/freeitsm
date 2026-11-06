# api/assets/get_asset_users.php

> API endpoint - 98 lines

API Endpoint: Get users assigned to an asset Returns list of users with assignment details

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_users.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


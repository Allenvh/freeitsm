# api/assets/get_asset_checkout_log.php

> API endpoint - 59 lines

API Endpoint: Check-in / check-out custody trail for an asset.

Returns the chronological log of checkout (assign) and checkin (unassign)
events — who held the asset, when, the due-back date, and which analyst
actioned it. Newest first.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_checkout_log.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [asset_checkout_log](/code/tables/asset_checkout_log.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


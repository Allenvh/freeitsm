# api/assets/update_asset_field.php

> API endpoint - 79 lines

API Endpoint: Update a single editable field on an asset. Thin UI adapter over AssetsService::updateFields — the validation, audit trail, and warranty-calendar sync live there, shared with the REST API's PATCH /assets/{id}.

## The endpoint

| | |
|---|---|
| URL | `api/assets/update_asset_field.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/assign_asset_user.php

> API endpoint - 60 lines

API Endpoint: Assign a user to an asset. Thin UI adapter over AssetsService::assignUser (creates the users_assets row, custody checkout, and audit trail). On a re-assign the UI passes `previous_user_id` so the audit records the "was X, now Y" transition.

## The endpoint

| | |
|---|---|
| URL | `api/assets/assign_asset_user.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


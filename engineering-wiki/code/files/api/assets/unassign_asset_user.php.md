# api/assets/unassign_asset_user.php

> API endpoint - 53 lines

API Endpoint: Remove a user from an asset. Thin UI adapter over AssetsService::unassignUser (deletes the users_assets row, custody checkin, and audit trail). `skip_audit` suppresses the history row when this removal is the first half of a re-assign (the assign call then logs the transition).

## The endpoint

| | |
|---|---|
| URL | `api/assets/unassign_asset_user.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


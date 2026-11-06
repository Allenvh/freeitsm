# api/assets/get_users_with_assets.php

> API endpoint - 37 lines

API: everyone currently holding at least one asset (discussion #56).

GET ?search=<text>   optional, matches name or email

Thin UI adapter over AssetsService::usersHoldingAssets(), which applies the
tenancy filter — so this returns only people holding assets the analyst is
allowed to see.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_users_with_assets.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET search` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


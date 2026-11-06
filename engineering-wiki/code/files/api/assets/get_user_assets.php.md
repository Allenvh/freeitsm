# api/assets/get_user_assets.php

> API endpoint - 48 lines

API: one person and everything currently assigned to them (discussion #56).

GET ?user_id=<int>

Feeds both the on-screen overview and the handover document, so the two can
never disagree about what somebody holds.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_user_assets.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET user_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


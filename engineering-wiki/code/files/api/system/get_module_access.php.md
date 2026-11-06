# api/system/get_module_access.php

> API endpoint - 68 lines

API: module-access summary (issue #30). Admins only. Returns the module registry, the current permission mode, and every team and analyst with their module grants (all-access flag + specific keys) — enough for the System → Modules summary + edit screen to render and edit without more calls.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_module_access.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [teams](/code/tables/teams.md) |  |
| 47 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getModulePermissionMode()](/code/functions/getModulePermissionMode.md), [getModuleRegistry()](/code/functions/getModuleRegistry.md)


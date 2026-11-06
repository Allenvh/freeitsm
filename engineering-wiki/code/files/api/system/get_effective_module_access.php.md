# api/system/get_effective_module_access.php

> API endpoint - 90 lines

API: effective module access for one analyst, with reasons (issue #30). Admins only. GET ?analyst_id=N → per-module { key, name, allowed, reason }, plus the analyst's name and the active mode. Mirrors `getAnalystAllowedModules()`'s resolution so the explanation always matches what enforcement will actually do.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_effective_module_access.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET analyst_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [analysts](/code/tables/analysts.md) |  |
| 31 |  | read [analyst_modules](/code/tables/analyst_modules.md) |  |
| 35 |  | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 42 |  | read [team_modules](/code/tables/team_modules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getModulePermissionMode()](/code/functions/getModulePermissionMode.md), [getModuleRegistry()](/code/functions/getModuleRegistry.md)


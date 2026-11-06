# api/system/save_module_grants.php

> API endpoint - 64 lines

API: save who specifically grants ONE module (issue #30). Admins only. POST { module_key: string, team_ids: int[], analyst_ids: int[] }

Reconciles the *specific* grants for this module — the team_modules / analyst_modules
rows. All-access entities (can_access_all_modules = 1) are managed by their own flag
(on the Analysts / Teams screens), not here, so they carry no rows and are ignored.

## The endpoint

| | |
|---|---|
| URL | `api/system/save_module_grants.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | **delete** [team_modules](/code/tables/team_modules.md) |  |
| 35 |  | **insert** [team_modules](/code/tables/team_modules.md) (`team_id`, `module_key`) |  |
| 36 |  | read [teams](/code/tables/teams.md) |  |
| 46 |  | **delete** [analyst_modules](/code/tables/analyst_modules.md) |  |
| 48 |  | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |
| 49 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getModuleRegistry()](/code/functions/getModuleRegistry.md)


# api/system/set_module_all_access.php

> API endpoint - 34 lines

API Endpoint: set the "access all modules" flag for one analyst or team.

Lets an admin grant/revoke all-module access straight from System -> Modules
without going back to the Analysts/Teams screens (issue #30). Admin-only.
POST { kind: 'analyst'|'team', id: int, all_modules: bool }

## The endpoint

| | |
|---|---|
| URL | `api/system/set_module_all_access.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)


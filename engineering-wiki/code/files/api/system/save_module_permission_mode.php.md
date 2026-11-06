# api/system/save_module_permission_mode.php

> API endpoint - 27 lines

API: set the site-wide module permission mode (issue #30). POST { mode: 'most' | 'least' }. Admins only. most = an analyst may use a module if ANY source (self or any team) grants it. least = only if their own access AND every team they're in grant it.

## The endpoint

| | |
|---|---|
| URL | `api/system/save_module_permission_mode.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)


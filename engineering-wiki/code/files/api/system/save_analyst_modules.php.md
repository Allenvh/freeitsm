# api/system/save_analyst_modules.php

> API endpoint - 62 lines

API: Save analyst module permissions POST - Updates module access for a specific analyst Input: { analyst_id: int, modules: string[] } Empty modules array = full access (all modules)

## The endpoint

| | |
|---|---|
| URL | `api/system/save_analyst_modules.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | **delete** [analyst_modules](/code/tables/analyst_modules.md) |  |
| 45 |  | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)


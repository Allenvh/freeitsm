# api/change-management/get_change_priorities.php

> API endpoint - 42 lines

API Endpoint: Get change priorities

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_change_priorities.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Called from | [assets/js/change-table.js](/code/files/assets/js/change-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [change_priorities](/code/tables/change_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


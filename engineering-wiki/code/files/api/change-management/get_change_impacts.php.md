# api/change-management/get_change_impacts.php

> API endpoint - 42 lines

API Endpoint: Get change impacts

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_change_impacts.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Called from | [assets/js/change-table.js](/code/files/assets/js/change-table.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [change_impacts](/code/tables/change_impacts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


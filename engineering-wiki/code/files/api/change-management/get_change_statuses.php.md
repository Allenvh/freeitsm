# api/change-management/get_change_statuses.php

> API endpoint - 43 lines

API Endpoint: Get change statuses

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_change_statuses.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [change_statuses](/code/tables/change_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


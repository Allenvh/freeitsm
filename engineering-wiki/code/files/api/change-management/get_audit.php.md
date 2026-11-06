# api/change-management/get_audit.php

> API endpoint - 49 lines

API Endpoint: Get audit trail for a change

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_audit.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET change_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [change_audit](/code/tables/change_audit.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


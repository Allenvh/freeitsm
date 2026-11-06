# api/change-management/get_comments.php

> API endpoint - 48 lines

API Endpoint: Get comments for a change

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_comments.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET change_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [change_comments](/code/tables/change_comments.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


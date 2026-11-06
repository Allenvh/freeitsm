# api/tickets/delete_user.php

> API endpoint - 88 lines

API Endpoint: Delete an end user

Refuses to delete if the user is referenced by any tickets or asset assignments,
so analyst-visible history is never silently broken.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_user.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 |  | read [tickets](/code/tables/tickets.md) |  |
| 68 |  | read [users_assets](/code/tables/users_assets.md) |  |
| 80 |  | **delete** [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


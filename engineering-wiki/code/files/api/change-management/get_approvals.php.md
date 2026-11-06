# api/change-management/get_approvals.php

> API endpoint - 142 lines

API Endpoint: Get changes pending approval Filters: all, requested (by me), assigned (to me as approver), cab (my CAB reviews)

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_approvals.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET filter` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [change_types](/code/tables/change_types.md), read [change_statuses](/code/tables/change_statuses.md), read [change_priorities](/code/tables/change_priorities.md), read [change_impacts](/code/tables/change_impacts.md) |  |
| 32 |  | read [changes](/code/tables/changes.md), read [change_cab_members](/code/tables/change_cab_members.md), read [analysts](/code/tables/analysts.md) |  |
| 55 |  | read [changes](/code/tables/changes.md), read [analysts](/code/tables/analysts.md) |  |
| 95 |  | read [change_cab_members](/code/tables/change_cab_members.md) |  |
| 107 |  | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 119 |  | read [change_cab_members](/code/tables/change_cab_members.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


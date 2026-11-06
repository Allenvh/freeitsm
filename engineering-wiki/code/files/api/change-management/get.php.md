# api/change-management/get.php

> API endpoint - 124 lines

API Endpoint: Get single change by ID with attachments and comments

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [changes](/code/tables/changes.md), read [change_types](/code/tables/change_types.md), read [change_statuses](/code/tables/change_statuses.md), read [change_priorities](/code/tables/change_priorities.md), read [change_impacts](/code/tables/change_impacts.md), read [analysts](/code/tables/analysts.md), read [change_categories](/code/tables/change_categories.md) |  |
| 68 |  | read [change_attachments](/code/tables/change_attachments.md) |  |
| 77 |  | read [change_comments](/code/tables/change_comments.md), read [analysts](/code/tables/analysts.md) |  |
| 91 |  | read [change_tickets](/code/tables/change_tickets.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 103 |  | read [change_cab_members](/code/tables/change_cab_members.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


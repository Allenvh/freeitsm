# api/change-management/list_linkable_tickets.php

> API endpoint - 63 lines

API: list open incidents (tickets) that can be linked to a change — scoped to the change's company, excluding closed/trashed ones and any already linked. Optional ?q search on number/subject. Used by the change detail's "Link incident" picker.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/list_linkable_tickets.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET change_id`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [changes](/code/tables/changes.md) |  |
| 41 |  | read [change_tickets](/code/tables/change_tickets.md) |  |
| 49 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


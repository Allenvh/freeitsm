# api/cmdb/get_object_tickets.php

> API endpoint - 98 lines

API: List tickets that reference a CMDB object. Powers the Activity panel on the object detail page. Two buckets: - open: tickets whose status is NOT closed (any non-closed lookup row) - closed: closed tickets, capped to the most recent 20 so the panel doesn't grow unbounded for objects with long histories

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_object_tickets.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [analysts](/code/tables/analysts.md), read [departments](/code/tables/departments.md) |  |
| 71 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)


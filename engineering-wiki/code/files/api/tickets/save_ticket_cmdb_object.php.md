# api/tickets/save_ticket_cmdb_object.php

> API endpoint - 86 lines

API: Link a CMDB object to a ticket. Idempotent — returns success even if the link already exists (the unique key catches dupes; we surface a friendly message rather than a SQL error).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_cmdb_object.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [tickets](/code/tables/tickets.md) |  |
| 40 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 56 |  | read [tickets](/code/tables/tickets.md) |  |
| 59 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 70 |  | **insert** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) (`ticket_id`, `cmdb_object_id`, `created_datetime`, `created_by_analyst_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


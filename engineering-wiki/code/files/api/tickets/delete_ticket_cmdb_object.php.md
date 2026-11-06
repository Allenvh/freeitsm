# api/tickets/delete_ticket_cmdb_object.php

> API endpoint - 52 lines

API: Unlink a CMDB object from a ticket. Accepts either the link row id (link_id), or a ticket_id + cmdb_object_id pair.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_cmdb_object.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |
| 36 |  | **delete** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |
| 42 |  | **delete** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


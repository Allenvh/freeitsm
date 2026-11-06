# api/tickets/get_ticket_cmdb_objects.php

> API endpoint - 63 lines

API: List the CMDB objects linked to a ticket. Returns hydrated info-card data (name, class, parent name+class, optional Owner) so the reading pane can render each link without follow-up calls.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_cmdb_objects.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 46 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


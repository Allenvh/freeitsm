# api/tickets/get_ticket_audit.php

> API endpoint - 106 lines

API Endpoint: Get ticket audit history Returns all audit entries for a ticket

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_audit.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [ticket_audit](/code/tables/ticket_audit.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_views.php](/code/files/includes/ticket_views.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketViewsAsAuditRows()](/code/functions/ticketViewsAsAuditRows.md)


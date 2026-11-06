# api/tickets/update_ticket_owner.php

> API endpoint - 34 lines

API Endpoint: Update ticket owner (assigned analyst). Thin UI adapter over TicketsService::updateTicket — an owner change is an assignment change, so it flows through the same path (sets assigned + owner, fires the ticket_assigned template + ticket.assigned workflow). writeAudit=false (the UI audits client-side).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/update_ticket_owner.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


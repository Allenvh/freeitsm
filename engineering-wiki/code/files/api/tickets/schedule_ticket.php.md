# api/tickets/schedule_ticket.php

> API endpoint - 43 lines

API Endpoint: Schedule work for a ticket (set/clear work_start_datetime). Thin UI adapter over TicketsService::updateTicket (writeAudit=false).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/schedule_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


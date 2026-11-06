# api/tickets/delete_ticket.php

> API endpoint - 29 lines

API Endpoint: Move a ticket to the trash (soft-delete). Thin UI adapter over TicketsService::deleteTicket. writeAudit=true — the UI records the 'Trash' audit row server-side.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


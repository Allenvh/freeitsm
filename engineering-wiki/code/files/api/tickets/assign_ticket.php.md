# api/tickets/assign_ticket.php

> API endpoint - 64 lines

API Endpoint: Update a ticket (department / type / status / origin / priority / assignment / first-time-fix / IT-training). Thin UI adapter over TicketsService::updateTicket. writeAudit=false — the UI writes its audit trail client-side (log_ticket_audit.php). The shared rules (closed_datetime, owner sync, template emails, CSAT, workflow dispatches) run in the service, exactly as this endpoint did inline before.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/assign_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)


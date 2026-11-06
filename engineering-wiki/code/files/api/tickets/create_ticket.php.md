# api/tickets/create_ticket.php

> API endpoint - 114 lines

API Endpoint: Create a new ticket manually. Thin UI adapter over TicketsService::createTicket — the initial email row, audit, and ticket.created workflow live there, shared with POST /api/v1/tickets. The acting company is the analyst's active tenant; a manual ticket auto-assigns to the creator unless another analyst is chosen.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/create_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


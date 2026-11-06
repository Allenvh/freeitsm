# api/tickets/delete_ticket_origin.php

> API endpoint - 82 lines

Delete Ticket Origin API — multi-tenancy aware (mirrors delete_ticket_type.php).

- Single-company / MSP-Default context → deletes a global default origin
    (blocked if any ticket uses it — today's behaviour).
  - Client company context → only that company's own origins; shared defaults
    are hidden (not deleted). Blocked while open tickets in the company use it.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_origin.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_TICKET_ORIGINS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 53 |  | read [tickets](/code/tables/tickets.md) |  |
| 65 |  | read [tickets](/code/tables/tickets.md) |  |
| 72 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 73 |  | **delete** [ticket_origins](/code/tables/ticket_origins.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


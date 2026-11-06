# api/tickets/delete_ticket_type.php

> API endpoint - 78 lines

API Endpoint: Delete ticket type — multi-tenancy aware.

- Single-company / MSP-Default context → deletes a global default type
    (today's behaviour).
  - Client company context → you can only delete THAT company's own types;
    shared defaults are hidden (not deleted) per company. Blocked if open
    tickets in the company still use the type (the in-use guard, design §7).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_type.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_TICKET_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 61 |  | read [tickets](/code/tables/tickets.md) |  |
| 68 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 69 |  | **delete** [ticket_types](/code/tables/ticket_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


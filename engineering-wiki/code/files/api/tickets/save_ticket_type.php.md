# api/tickets/save_ticket_type.php

> API endpoint - 107 lines

API Endpoint: Save ticket type (create or update) — multi-tenancy aware.

Context decides scope (design §7):
  - Single-company install, OR working in the MSP/Default company → the type is
    a GLOBAL default (tenant_id NULL). Exactly today's behaviour.
  - Working in a client company's context → a NEW type belongs to THAT company
    (tenant_id set). You may edit/delete only that company's own types; the
    shared defaults are managed from the MSP/Default context (and hidden, not
    edited, per company).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_type.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_TICKET_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 57 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 59 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 73 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 94 |  | **update** [ticket_types](/code/tables/ticket_types.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 97 |  | **insert** [ticket_types](/code/tables/ticket_types.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


# api/tickets/save_ticket_origin.php

> API endpoint - 105 lines

Save Ticket Origin API — Create or Update, multi-tenancy aware (mirrors save_ticket_type.php).

Context decides scope: single-company / MSP-Default → a GLOBAL default origin;
a client company's context → a NEW origin belongs to THAT company. You may
edit only your own; shared defaults are managed from the Default context and
hidden (not edited) per company.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_origin.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_TICKET_ORIGINS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 54 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 56 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 69 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 90 |  | **update** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 95 |  | **insert** [ticket_origins](/code/tables/ticket_origins.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


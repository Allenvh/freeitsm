# api/tickets/set_ticket_origin_hidden.php

> API endpoint - 70 lines

API: Hide / show a global ticket origin for the active company. The "hide" half of the add+hide model (design §7) — mirrors set_ticket_type_hidden.php. POST JSON { ticket_origin_id, hidden: true|false }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/set_ticket_origin_hidden.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_TICKET_ORIGINS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 53 |  | read [tickets](/code/tables/tickets.md) |  |
| 58 |  | **insert** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) (`tenant_id`, `entity_type`, `entity_id`) |  |
| 61 |  | **delete** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/tickets/save_ticket_resolution_code.php

> API endpoint - 94 lines

API Endpoint: create or update a resolution code (#1540).

POST { id?, name, description?, is_active?, display_order? }

A resolution code answers HOW a ticket ended — "Training given", "No fault
found" — which is not the same question as what it was about. Flat on
purpose: the moment this grows a hierarchy it has become a second category
tree and the two will drift apart.

⚠️ The unique key is (tenant_id, name), and MySQL allows unlimited NULLs in a
unique key — so two GLOBAL codes with the same name would slip past it. Name
uniqueness is enforced in the query below, same as ticket_types.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_resolution_code.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CATEGORIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 |  | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 64 |  | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 78 |  | **update** [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) (`name`, `description`, `is_active`, `display_order`) |  |
| 83 |  | **insert** [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) (`name`, `description`, `is_active`, `display_order`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


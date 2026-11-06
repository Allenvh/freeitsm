# api/tickets/delete_ticket_resolution_code.php

> API endpoint - 69 lines

API Endpoint: delete a resolution code (#1540).

⚠️ Same rule as categories: retiring (is_active = 0) is the normal way to
remove one, so a closed ticket keeps the code it was closed with. Deleting is
only allowed while no ticket points at it. The foreign key would RESTRICT this
anyway; the check exists to say WHY in words first.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_resolution_code.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CATEGORIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 51 |  | read [tickets](/code/tables/tickets.md) |  |
| 61 |  | **delete** [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


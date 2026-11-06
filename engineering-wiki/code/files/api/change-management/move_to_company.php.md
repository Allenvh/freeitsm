# api/change-management/move_to_company.php

> API endpoint - 78 lines

API Endpoint: move a change to another company (tenant).

The Change Management twin of api/tickets/move_ticket_to_company.php. Gated
both ways — the analyst must be able to access the change as it currently
sits AND the company they're moving it into. Writes a change_audit entry
recording the change of company. No-op surface on a single-company install.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/move_to_company.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [changes](/code/tables/changes.md) |  |
| 62 |  | **update** [changes](/code/tables/changes.md) (`tenant_id`, `modified_datetime`) |  |
| 68 |  | **insert** [change_audit](/code/tables/change_audit.md) (`change_id`, `analyst_id`, `action_type`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


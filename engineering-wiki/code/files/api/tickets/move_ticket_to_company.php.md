# api/tickets/move_ticket_to_company.php

> API endpoint - 78 lines

API Endpoint: move a ticket to another company (tenant).

The fix for "sticky" wrong-company filing: a misrouted ticket can be re-homed to
the correct company. Gated both ways — the analyst must be able to access the
ticket as it stands AND the company they're moving it into. Writes a ticket_audit
entry recording the change. No-op surface on a single-company install.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/move_ticket_to_company.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [tickets](/code/tables/tickets.md) |  |
| 62 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`, `updated_datetime`) |  |
| 68 |  | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


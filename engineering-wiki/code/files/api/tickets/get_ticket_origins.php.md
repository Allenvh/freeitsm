# api/tickets/get_ticket_origins.php

> API endpoint - 112 lines

Get Ticket Origins API — multi-tenancy aware (mirrors get_ticket_types.php).

Ticket origins are a "global default + per-company add/hide" list (design §7):
global defaults (tenant_id NULL) + each company's own + per-company hides.

  - default (consumer): `origins` = the RESOLVED visible list for the active
    company (global-not-hidden + own). On a single-company install = all rows.
  - ?manage=1 (settings): adds `scoped` (own + shared-defaults-with-hidden-flag)
    when working in a *client* company's context.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_origins.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET manage` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 75 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 86 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [getTenantConfigRowsByCompany()](/code/functions/getTenantConfigRowsByCompany.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


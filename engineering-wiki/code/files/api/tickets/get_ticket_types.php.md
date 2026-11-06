# api/tickets/get_ticket_types.php

> API endpoint - 124 lines

API Endpoint: Get ticket types — multi-tenancy aware.

Ticket types are a "global default + per-company add/hide" list (design §7):
  - global defaults  → rows with tenant_id IS NULL (shared by every company)
  - a company's own  → rows with tenant_id = that company
  - a company can hide a global default from its own lists (tenant_config_hidden)

Two response shapes:
  - default (consumer, e.g. the ticket form): `ticket_types` = the RESOLVED
    visible list for the active company (global-not-hidden + own). On a
    single-company install this is simply every type — exactly as before.
  - ?manage=1 (the settings screen): additionally returns `scoped` describing
    the two groups to manage when working in a *client* company's context.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_types.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET manage` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 87 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 98 |  | read [ticket_types](/code/tables/ticket_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [getTenantConfigRowsByCompany()](/code/functions/getTenantConfigRowsByCompany.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)


# api/assets/get_asset_types.php

> API endpoint - 120 lines

API Endpoint: Get asset types — multi-tenancy aware.

Asset types are a "global default + per-company add/hide" list (mirrors ticket
types, design §7):
  - global defaults  → rows with tenant_id IS NULL (shared by every company)
  - a company's own  → rows with tenant_id = that company
  - a company can hide a global default from its own lists (tenant_config_hidden)

Two response shapes:
  - default (consumer, e.g. the asset edit form): `asset_types` = the RESOLVED
    visible list for the active company. On a single-company install this is
    simply every type — exactly as before.
  - ?manage=1 (the settings screen): additionally returns `scoped` describing
    the two groups to manage when working in a *client* company's context.

read_and_close releases the session lock immediately so parallel AJAX calls
from the same page don't queue behind PHP's exclusive session lock (see #388).

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_types.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET manage` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 |  | read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 78 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 83 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 94 |  | read [asset_types](/code/tables/asset_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requestedTenantId()](/code/functions/requestedTenantId.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


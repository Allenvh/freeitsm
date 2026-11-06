# api/assets/get_asset_status_types.php

> API endpoint - 94 lines

API Endpoint: Get asset status types — multi-tenancy aware.

A "global default + per-company add/hide" list (mirrors asset/ticket types).
  - default (consumer): `asset_status_types` = the RESOLVED visible list for
    the active company (every row on a single-company install).
  - ?manage=1 (settings): additionally returns `scoped` (globals + own) for a
    client company's context.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_status_types.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET manage` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 57 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 68 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requestedTenantId()](/code/functions/requestedTenantId.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/get_asset_locations.php

> API endpoint - 70 lines

API Endpoint: List asset locations (flat) — multi-tenancy aware.

Locations are an arbitrary-depth tree (the client builds it from parent_id),
and they are PER COMPANY (a client's physical sites are its own), EXCEPT the
ones marked shared (2.6.0), which every company sees. So the tree is scoped
like the assets themselves: the Default company owns the pre-existing
(NULL-tenant) locations, and each client company sees its own plus the shared
ones. On a single-company install this is simply every location, as before.

Used by both the settings tree and the asset location picker, so scoping here
scopes both.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_locations.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_locations.php](/code/files/includes/asset_locations.php.md)

**Calls:** [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md), [assetLocationScope()](/code/functions/assetLocationScope.md), [assetLocationsSharedReady()](/code/functions/assetLocationsSharedReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requestedTenantId()](/code/functions/requestedTenantId.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


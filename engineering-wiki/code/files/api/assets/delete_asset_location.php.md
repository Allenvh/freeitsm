# api/assets/delete_asset_location.php

> API endpoint - 86 lines

API Endpoint: Delete an asset location — multi-tenancy aware.

Refuses to delete a node that still has children (re-parent/remove them
first; the DB self-ref FK is RESTRICT as a backstop). Per company: you may
delete only what this context owns — a company's own locations in its
context, the shared ones from the MSP/Default context. Assets pointing at the
deleted location have their location cleared (FK ON DELETE SET NULL).

## The endpoint

| | |
|---|---|
| URL | `api/assets/delete_asset_location.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_LOCATIONS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 70 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 76 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 77 |  | **delete** [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_locations.php](/code/files/includes/asset_locations.php.md)

**Calls:** [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md), [assetLocationIsShared()](/code/functions/assetLocationIsShared.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


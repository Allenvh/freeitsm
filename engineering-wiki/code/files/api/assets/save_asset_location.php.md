# api/assets/save_asset_location.php

> API endpoint - 175 lines

API Endpoint: Save an asset location (create or update) — multi-tenancy aware.

A location is one node in an arbitrary-depth tree, and locations are PER
COMPANY (a client's sites are its own). A save in the Default context is the
Default company's (tenant_id NULL); a save in a client company's context is
that company's own. A node's parent must belong to the same company, or be
shared. Re-parenting rejects cycles (a node can't sit inside its own subtree).

2.6.0 SHARED locations (`is_shared`): every company can pick one. The rules
are in includes/asset_locations.php; this endpoint enforces them.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_location.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_LOCATIONS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 82 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 100 |  | read [assets](/code/tables/assets.md) |  |
| 106 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 137 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 150 |  | **update** [asset_locations](/code/tables/asset_locations.md) (`name`, `parent_id`, `display_order`, `is_shared`, `tenant_id`) |  |
| 154 |  | **update** [asset_locations](/code/tables/asset_locations.md) (`name`, `parent_id`, `display_order`) |  |
| 161 |  | **insert** [asset_locations](/code/tables/asset_locations.md) (`name`, `parent_id`, `display_order`, `tenant_id`, `is_shared`) |  |
| 164 |  | **insert** [asset_locations](/code/tables/asset_locations.md) (`name`, `parent_id`, `display_order`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_locations.php](/code/files/includes/asset_locations.php.md)

**Calls:** [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md), [assetLocationIsShared()](/code/functions/assetLocationIsShared.md), [assetLocationScope()](/code/functions/assetLocationScope.md), [assetLocationsSharedReady()](/code/functions/assetLocationsSharedReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


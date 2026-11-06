# api/assets/create_asset.php

> API endpoint - 149 lines

API Endpoint: create an asset from the UI.

Until now the ONLY way to create an asset was the inventory agent, Intune,
vCenter or POST /api/v1/assets — every one of which assumes the thing reports
for itself. A television, a printer or a headset never will, so recording one
meant a curl command. This is the front door.

Thin adapter over AssetsService::createAsset — the duplicate-hostname refusal,
field validation and audit trail all live there, shared with the REST API.

Deliberately CORE FIELDS ONLY. Custom fields are filled in on the asset
itself, where there is room for them and the "3 of 3 filled in" counter says
what is outstanding; a modal is the wrong place to answer eight questions.

## The endpoint

| | |
|---|---|
| URL | `api/assets/create_asset.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 98 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 120 |  | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/asset_locations.php](/code/files/includes/asset_locations.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [assetLocationScope()](/code/functions/assetLocationScope.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [t()](/code/functions/t.md), [wf_emit()](/code/functions/wf_emit.md)


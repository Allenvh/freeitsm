# api/assets/delete_asset_type.php

> API endpoint - 81 lines

API Endpoint: Delete asset type — multi-tenancy aware.

- Single-company / MSP-Default context → deletes a global default type
    (today's behaviour: any assets referencing it are nulled first).
  - Client company context → you can only delete THAT company's own types;
    shared defaults are hidden (not deleted) per company. Blocked if assets in
    the company still use the type (the in-use guard, design §7).

## The endpoint

| | |
|---|---|
| URL | `api/assets/delete_asset_type.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 55 |  | **update** [assets](/code/tables/assets.md) (`asset_type_id`) |  |
| 64 |  | read [assets](/code/tables/assets.md) |  |
| 71 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 72 |  | **delete** [asset_types](/code/tables/asset_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


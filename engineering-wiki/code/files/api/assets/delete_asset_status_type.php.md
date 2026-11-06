# api/assets/delete_asset_status_type.php

> API endpoint - 78 lines

API Endpoint: Delete asset status type — multi-tenancy aware.

- Single-company / MSP-Default context → deletes a global default status
    (assets referencing it are nulled first).
  - Client company context → delete only THAT company's own statuses; shared
    defaults are hidden per company. Blocked if assets in the company still
    use the status (the in-use guard).

## The endpoint

| | |
|---|---|
| URL | `api/assets/delete_asset_status_type.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 53 |  | **update** [assets](/code/tables/assets.md) (`asset_status_id`) |  |
| 61 |  | read [assets](/code/tables/assets.md) |  |
| 68 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 69 |  | **delete** [asset_status_types](/code/tables/asset_status_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


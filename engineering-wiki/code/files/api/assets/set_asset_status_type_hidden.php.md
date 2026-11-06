# api/assets/set_asset_status_type_hidden.php

> API endpoint - 73 lines

API Endpoint: Hide / show a global asset status for the active company.

The "hide" half of the add+hide override model. Reversible; the global row is
never touched. POST JSON { asset_status_type_id, hidden: true|false }. Only
meaningful in a client company's context; blocked while assets in the company
still use the status (in-use guard).

## The endpoint

| | |
|---|---|
| URL | `api/assets/set_asset_status_type_hidden.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 56 |  | read [assets](/code/tables/assets.md) |  |
| 61 |  | **insert** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) (`tenant_id`, `entity_type`, `entity_id`) |  |
| 64 |  | **delete** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


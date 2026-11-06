# api/assets/save_asset_status_type.php

> API endpoint - 100 lines

API Endpoint: Save asset status type (create or update) — multi-tenancy aware.

Context decides scope (mirrors save_asset_type.php, design §7): a save in the
MSP/Default context is a GLOBAL default (tenant_id NULL); a save in a client
company's context is that company's own. You may edit/delete only a company's
own statuses; shared defaults are hidden (not edited) per company.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_status_type.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 51 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 53 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 66 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 87 |  | **update** [asset_status_types](/code/tables/asset_status_types.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 90 |  | **insert** [asset_status_types](/code/tables/asset_status_types.md) (`name`, `description`, `display_order`, `is_active`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


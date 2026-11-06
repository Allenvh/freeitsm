# api/assets/save_asset_type.php

> API endpoint - 110 lines

API Endpoint: Save asset type (create or update) — multi-tenancy aware.

Context decides scope (mirrors save_ticket_type.php, design §7):
  - Single-company install, OR working in the MSP/Default company → the type is
    a GLOBAL default (tenant_id NULL). Exactly today's behaviour.
  - Working in a client company's context → a NEW type belongs to THAT company
    (tenant_id set). You may edit/delete only that company's own types; the
    shared defaults are managed from the MSP/Default context (and hidden, not
    edited, per company).

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_type.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 60 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 62 |  | read [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |
| 76 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 97 |  | **update** [asset_types](/code/tables/asset_types.md) (`name`, `description`, `display_order`, `is_active`, `icon_id`) |  |
| 100 |  | **insert** [asset_types](/code/tables/asset_types.md) (`name`, `description`, `display_order`, `is_active`, `icon_id`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


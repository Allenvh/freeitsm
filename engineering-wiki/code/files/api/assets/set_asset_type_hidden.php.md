# api/assets/set_asset_type_hidden.php

> API endpoint - 82 lines

API Endpoint: Hide / show a global asset type for the active company.

The "hide" half of the add+hide override model (mirrors ticket types, design
§7). A company can remove a shared default type from its own pickers without
affecting any other company — the global row itself is never touched, so
existing assets still resolve it, and the action is fully reversible.

POST JSON { asset_type_id, hidden: true|false }

Only meaningful inside a client company's context (not at N=1, not in the
MSP/Default context). Hiding is blocked while assets in the company still use
the type — the in-use guard.

## The endpoint

| | |
|---|---|
| URL | `api/assets/set_asset_type_hidden.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_TYPES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 65 |  | read [assets](/code/tables/assets.md) |  |
| 70 |  | **insert** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) (`tenant_id`, `entity_type`, `entity_id`) |  |
| 73 |  | **delete** [tenant_config_hidden](/code/tables/tenant_config_hidden.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


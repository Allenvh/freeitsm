# api/assets/toggle_supplier_assets.php

> API endpoint - 36 lines

API Endpoint: Toggle whether a supplier is available for assets. Flips suppliers.supplies_assets for a given supplier id.

## The endpoint

| | |
|---|---|
| URL | `api/assets/toggle_supplier_assets.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_SUPPLIERS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | **update** [suppliers](/code/tables/suppliers.md) (`supplies_assets`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


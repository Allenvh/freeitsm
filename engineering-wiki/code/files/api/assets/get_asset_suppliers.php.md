# api/assets/get_asset_suppliers.php

> API endpoint - 33 lines

API Endpoint: Suppliers available for assets (dropdown source).

Returns only suppliers flagged supplies_assets (and active) from the shared
suppliers registry, labelled by trading name (falling back to legal name).

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_suppliers.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [suppliers](/code/tables/suppliers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/save_asset_field_set.php

> API endpoint - 33 lines

API: Create or update a field SET, and (optionally) replace its field list in the same request — so one drag-reorder is one call and the order can never be half-applied.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_field_set.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_FIELDS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


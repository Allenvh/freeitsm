# api/assets/save_type_field_sets.php

> API endpoint - 33 lines

API: Replace which field sets an asset TYPE carries. Every Television gets these fields.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_type_field_sets.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_FIELDS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


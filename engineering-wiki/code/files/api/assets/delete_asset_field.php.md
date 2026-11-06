# api/assets/delete_asset_field.php

> API endpoint - 32 lines

API: Retire a custom asset field.

⚠️ SOFT delete. The values recorded against it are kept — see
AssetFieldsService::deleteField() and the RESTRICT foreign key that makes a
hard delete impossible even by hand.

## The endpoint

| | |
|---|---|
| URL | `api/assets/delete_asset_field.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_FIELDS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


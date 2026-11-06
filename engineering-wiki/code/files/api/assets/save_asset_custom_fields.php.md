# api/assets/save_asset_custom_fields.php

> API endpoint - 36 lines

API: save custom field values for one asset.

Takes { asset_id, values: { field_key: value } }. Only fields that actually
apply to this asset are accepted — an inapplicable key is a hard error, never
a silent drop, so a mapping mistake surfaces instead of going quiet.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_custom_fields.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


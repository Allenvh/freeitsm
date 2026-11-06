# api/assets/get_asset_fields.php

> API endpoint - 121 lines

API Endpoint: the custom asset field catalogue, its sets, and which sets each asset type carries. Everything the settings screen needs, in one call.

Read-only, so it needs module access but not the design capability — the
asset detail page reads this too, and a field's LABEL is not a secret.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_fields.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [asset_field_options](/code/tables/asset_field_options.md) |  |
| 50 |  | read [asset_field_set_fields](/code/tables/asset_field_set_fields.md) |  |
| 51 |  | read [asset_field_values](/code/tables/asset_field_values.md) |  |
| 75 |  | read [asset_field_set_fields](/code/tables/asset_field_set_fields.md), read [asset_fields](/code/tables/asset_fields.md) |  |
| 82 |  | read [asset_type_field_sets](/code/tables/asset_type_field_sets.md) |  |
| 83 |  | read [asset_field_set_assets](/code/tables/asset_field_set_assets.md) |  |
| 103 |  | read [asset_type_field_sets](/code/tables/asset_type_field_sets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


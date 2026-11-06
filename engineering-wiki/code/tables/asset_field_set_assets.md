# asset_field_set_assets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `set_id` | `INT NOT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):449 | `AssetFieldsService::attachSetToAsset` | insert | `asset_id`, `set_id`, `created_by_analyst_id` |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):469 | `AssetFieldsService::detachSetFromAsset` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):621 | `AssetFieldsService::deleteSet` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md) | L83 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::setsForAsset L133 |  |


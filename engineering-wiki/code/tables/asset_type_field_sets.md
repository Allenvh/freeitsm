# asset_type_field_sets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_type_id` | `INT NOT NULL` |  |
| `set_id` | `INT NOT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):620 | `AssetFieldsService::deleteSet` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):685 | `AssetFieldsService::setTypeSets` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):687 | `AssetFieldsService::setTypeSets` | insert | `asset_type_id`, `set_id`, `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md) | L82, L103 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::setsForAsset L119 |  |


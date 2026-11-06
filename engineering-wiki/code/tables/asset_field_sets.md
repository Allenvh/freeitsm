# asset_field_sets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` |  |
| `is_deleted` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):597 | `AssetFieldsService::saveSet` | update | `name`, `description`, `display_order` |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):602 | `AssetFieldsService::saveSet` | insert | `name`, `description`, `display_order`, `tenant_id` |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):619 | `AssetFieldsService::deleteSet` | update | `is_deleted` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::setsForAsset L119, AssetFieldsService::setsForAsset L133, AssetFieldsService::fieldsForSets L184, AssetFieldsService::loadSet L923 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::assetsForUser L1175 |  |


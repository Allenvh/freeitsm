# asset_history

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `field_name` | `VARCHAR(100) NOT NULL` |  |
| `old_value` | `VARCHAR(500) NULL` |  |
| `new_value` | `VARCHAR(500) NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2261 |  | update | `field_name` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):745 | `AssetsService::auditWrite` | insert | `asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):403 | `AssetFieldsService::logChanges` | insert | `asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value` |  |
| [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md):431 | `AssetTagsService::assign` | insert | `asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_history.php](/code/files/api/assets/get_asset_history.php.md) | L34 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L1044 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetHistoryList L462 |  |
| [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md) | dbPreviewRepairs L148 |  |


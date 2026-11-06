# asset_field_values

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `field_id` | `INT NOT NULL` |  |
| `seq` | `INT NOT NULL DEFAULT 0` |  |
| `value_text` | `TEXT NULL` |  |
| `value_number` | `DECIMAL(20,4) NULL` |  |
| `value_date` | `DATETIME NULL` |  |
| `value_boolean` | `TINYINT(1) NULL` |  |
| `value_ref_id` | `INT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md) | L51 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L167 |  |
| [asset-management/labels.php](/code/files/asset-management/labels.php.md) | L129 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::schemaReady L84, AssetFieldsService::assertUnique L364, AssetFieldsService::fieldHasValues L832 |  |


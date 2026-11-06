# asset_field_set_fields

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `set_id` | `INT NOT NULL` |  |
| `field_id` | `INT NOT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_required` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `default_value` | `VARCHAR(255) NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):577 | `AssetFieldsService::deleteField` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):642 | `AssetFieldsService::setSetFields` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):644 | `AssetFieldsService::setSetFields` | insert | `set_id`, `field_id`, `sort_order`, `is_required`, `default_value` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md) | L50, L75 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::fieldsForSets L184 |  |


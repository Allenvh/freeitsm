# asset_field_options

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `field_id` | `INT NOT NULL` |  |
| `option_value` | `VARCHAR(255) NOT NULL` |  |
| `colour` | `VARCHAR(7) NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):806 | `AssetFieldsService::saveOptions` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):809 | `AssetFieldsService::saveOptions` | delete |  |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):811 | `AssetFieldsService::saveOptions` | insert | `field_id`, `option_value`, `colour`, `display_order` |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md):781 | `AssetImportService::resolveOption` | insert | `field_id`, `option_value`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_custom_fields.php](/code/files/api/assets/get_asset_custom_fields.php.md) | assetFieldOptions L39 |  |
| [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md) | L48 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::valueSchema L69 |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) | AssetImportService::resolveOption L772 |  |


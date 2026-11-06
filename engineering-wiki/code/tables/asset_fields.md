# asset_fields

Custom asset fields (docs/design/flexible-asset-fields.md) The catalogue, the sets that bundle fields, the two ways a set attaches (to a type, or to one asset), and the answers.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `field_key` | `VARCHAR(100) NOT NULL` |  |
| `label` | `VARCHAR(150) NOT NULL` |  |
| `field_type` | `VARCHAR(20) NOT NULL` |  |
| `config` | `LONGTEXT NULL` |  |
| `help_text` | `VARCHAR(500) NULL` |  |
| `is_unique` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `is_searchable` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `show_in_list` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` |  |
| `is_deleted` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):522 | `AssetFieldsService::saveField` | update | `label`, `field_type`, `config`, `help_text`, `is_unique`, `is_searchable`, `show_in_list` |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):546 | `AssetFieldsService::saveField` | insert | `field_key`, `label`, `field_type`, `config`, `help_text`, `is_unique`, `is_searchable`, `show_in_list`, `tenant_id` |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md):576 | `AssetFieldsService::deleteField` | update | `is_deleted` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_fields.php](/code/files/api/assets/get_asset_fields.php.md) | L75 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L134 |  |
| [asset-management/labels.php](/code/files/asset-management/labels.php.md) | L129 |  |
| [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) | assetLabelCustomFields L209 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::fieldsForSets L184, AssetFieldsService::assertKeyFree L761, AssetFieldsService::assertKeyFree L765, AssetFieldsService::loadField L844 |  |
| [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md) | HandoverTemplates::customColumns L115 |  |


# asset_types

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `icon_id` | `INT NULL` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy config: NULL = global default type, set = a company's own. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md):72 |  | delete |  |  |
| [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md):97 |  | update | `name`, `description`, `display_order`, `is_active`, `icon_id` |  |
| [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md):100 |  | insert | `name`, `description`, `display_order`, `is_active`, `icon_id`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md) | L42, L71 |  |
| [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md) | L52 |  |
| [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md) | L83, L94 |  |
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L146 |  |
| [api/assets/get_widget_data.php](/code/files/api/assets/get_widget_data.php.md) | L61 |  |
| [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md) | L59 |  |
| [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md) | L59, L60, L76 |  |
| [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md) | L53 |  |
| [api/self-service/get_my_assets.php](/code/files/api/self-service/get_my_assets.php.md) | L37 |  |
| [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md) | L39 |  |
| [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md) | L49 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSelect L52 |  |
| [asset-management/labels.php](/code/files/asset-management/labels.php.md) | L104 |  |
| [asset-management/scan.php](/code/files/asset-management/scan.php.md) | L65 |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) | contractAssetSelect L64, contractAssetSearch L263 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleAssets L374, peopleSupplierAssets L850 |  |
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetsFor L199, projectTargetOptions L317 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewAsset L306 |  |
| [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) | L14 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::assetsForUser L1135 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolAssetLookup L569 |  |


# asset_status_types

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy config: NULL = global default status, set = a company's own. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md):69 |  | delete |  |  |
| [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md):87 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md):90 |  | insert | `name`, `description`, `display_order`, `is_active`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md) | L41, L68 |  |
| [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md) | L52 |  |
| [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md) | L57, L68 |  |
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L149 |  |
| [api/assets/get_widget_data.php](/code/files/api/assets/get_widget_data.php.md) | L68 |  |
| [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md) | L59 |  |
| [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md) | L50, L51, L66 |  |
| [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md) | L45 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSelect L52 |  |
| [asset-management/scan.php](/code/files/asset-management/scan.php.md) | L65 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleAssets L374, peopleSupplierAssets L850 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L189 |  |
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetAssets L297, projectTargetOptions L318 |  |
| [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) | L15 |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) | AssetImportService::inactiveStatusId L864 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::assetsForUser L1135 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolAssetLookup L569 |  |


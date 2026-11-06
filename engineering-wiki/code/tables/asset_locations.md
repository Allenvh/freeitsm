# asset_locations

Arbitrary-depth physical location tree (adjacency list). Self-ref FK + parent index added in the post-schema section below.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `parent_id` | `INT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy SCOPED DATA (not a config list, unlike the two lists above): a company's sites are entirely its own, so NULL = the Default company's, set = that company's. Read via `activeTenantFilter()`. |
| `is_shared` | `TINYINT(1) NOT NULL DEFAULT 0` | 2.6.0: a location every company can pick (a shared data centre or head office). Stored with tenant_id NULL. See includes/asset_locations.php. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md):77 |  | delete |  |  |
| [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md):150 |  | update | `name`, `parent_id`, `display_order`, `is_shared`, `tenant_id` |  |
| [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md):154 |  | update | `name`, `parent_id`, `display_order` |  |
| [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md):161 |  | insert | `name`, `parent_id`, `display_order`, `tenant_id`, `is_shared` |  |
| [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md):164 |  | insert | `name`, `parent_id`, `display_order`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md) | L98 |  |
| [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md) | L43, L70, L76 |  |
| [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md) | L52 |  |
| [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md) | L43 |  |
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L221, L262 |  |
| [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md) | L59 |  |
| [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md) | L68, L82, L106, L137 |  |
| [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md) | L39 |  |
| [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md) | L49 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSelect L52, apiAssetLocations L72 |  |
| [asset-management/labels.php](/code/files/asset-management/labels.php.md) | L104 |  |
| [asset-management/scan.php](/code/files/asset-management/scan.php.md) | L65 |  |
| [includes/asset_locations.php](/code/files/includes/asset_locations.php.md) | assetLocationIsShared L65 |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) | contractAssetSelect L64, contractAssetSearch L263 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseDueBack L778 |  |
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetAssets L297, projectTargetOptions L319 |  |
| [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) | L19 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::moveToCompany L679, AssetsService::assetsForUser L1135 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolAssetLookup L569 |  |


# asset_checkout_log

Check-in / check-out custody trail. FK + index in post-schema section.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `user_id` | `INT NULL` |  |
| `user_name` | `VARCHAR(150) NULL` |  |
| `action` | `VARCHAR(10) NOT NULL` |  |
| `expected_return_date` | `DATE NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `notes` | `VARCHAR(500) NULL` |  |
| `action_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):366 | `AssetsService::assignAnalyst` | insert | `asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `notes`, `action_datetime` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):437 | `AssetsService::assignUser` | insert | `asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `notes`, `action_datetime` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):494 | `AssetsService::unassignUser` | insert | `asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `action_datetime` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):541 | `AssetsService::unassignAnalyst` | insert | `asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `action_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_checkout_log.php](/code/files/api/assets/get_asset_checkout_log.php.md) | L44 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetCustodyList L484 |  |
| [asset-management/scan.php](/code/files/asset-management/scan.php.md) | L88 |  |


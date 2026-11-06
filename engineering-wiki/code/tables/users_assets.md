# users_assets

Who is holding an asset. EITHER a requester (user_id) OR a member of the desk (analyst_id) - never both, never neither. TicketsService' sibling AssetsService enforces that; the database cannot, because a CHECK across two columns is not portable to the MySQL versions this supports.  🔴 user_id is NULLABLE, and was not. Assets could only ever be assigned to a requester, and analysts are not requesters - on a real install most of the desk had no `users` row at all, so an analyst holding a laptop could not be recorded. api/system/db_verify.php relaxes the column on existing installs, the same probe-then-MODIFY it has used five times before.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `user_id` | `INT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `asset_id` | `INT NOT NULL` |  |
| `assigned_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `assigned_by_analyst_id` | `INT NULL` |  |
| `notes` | `VARCHAR(500) NULL` |  |
| `expected_return_date` | `DATE NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):357 | `AssetsService::assignAnalyst` | insert | `asset_id`, `analyst_id`, `assigned_by_analyst_id`, `notes`, `expected_return_date`, `assigned_datetime` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):430 | `AssetsService::assignUser` | insert | `asset_id`, `user_id`, `assigned_by_analyst_id`, `notes`, `expected_return_date`, `assigned_datetime` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):490 | `AssetsService::unassignUser` | delete |  |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):533 | `AssetsService::unassignAnalyst` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_users.php](/code/files/api/assets/get_asset_users.php.md) | L60 |  |
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L136 |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md) | L167 |  |
| [api/self-service/get_my_assets.php](/code/files/api/self-service/get_my_assets.php.md) | L37 |  |
| [api/tickets/delete_user.php](/code/files/api/tickets/delete_user.php.md) | L68 |  |
| [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md) | L70 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSelect L52, apiAssetsList L274, apiAssetsList L278, apiAssetsGet L353, apiAssetAssignmentsList L419 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseGet L419, leaseDueBack L778 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleAssets L376, peopleAssets L382, peopleSupplierAssets L857 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewAsset L306 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::assignAnalyst L348, AssetsService::assignUser L423, AssetsService::unassignUser L480, AssetsService::unassignAnalyst L523, AssetsService::moveToCompany L652, AssetsService::usersHoldingAssets L913 ... |  |
| [self-service/includes/header.php](/code/files/self-service/includes/header.php.md) | L137 |  |


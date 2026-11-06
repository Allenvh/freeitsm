# lease_assets

An asset on a schedule; its own start/end when they differ from the schedule's.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `schedule_id` | `INT NOT NULL` |  |
| `asset_id` | `INT NOT NULL` |  |
| `outcome_id` | `INT NULL` |  |
| `start_date` | `DATE NULL` |  |
| `end_date` | `DATE NULL` |  |
| `outcome_date` | `DATE NULL` |  |
| `notes` | `VARCHAR(500) NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/leases.php](/code/files/includes/leases.php.md):580 | `leaseAssetsAdd` | insert | `schedule_id`, `asset_id`, `outcome_id`, `created_by`, `created_datetime`, `updated_datetime` |  |
| [includes/leases.php](/code/files/includes/leases.php.md):654 | `leaseAssetUpdate` | update | `outcome_id`, `start_date`, `end_date`, `outcome_date`, `notes`, `updated_datetime` |  |
| [includes/leases.php](/code/files/includes/leases.php.md):669 | `leaseAssetRemove` | delete |  |  |
| [includes/leases.php](/code/files/includes/leases.php.md):733 | `leaseAssetDatesEdited` | update | `start_date`, `end_date`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/delete_lease_outcome.php](/code/files/api/assets/delete_lease_outcome.php.md) | L32 |  |
| [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md) | L22 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseReady L44, leaseGoneBackSql L146, leaseSave L232, leaseList L339, leaseGet L419, leaseScheduleSave L488 ... |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::moveToCompany L666 |  |


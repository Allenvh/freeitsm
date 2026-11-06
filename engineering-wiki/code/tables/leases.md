# leases

The agreement. Scoped data like assets: NULL tenant = the Default company.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `name` | `VARCHAR(200) NOT NULL` |  |
| `reference` | `VARCHAR(100) NULL` |  |
| `supplier_id` | `INT NULL` |  |
| `cost_centre_id` | `INT NULL` |  |
| `contract_id` | `INT NULL` |  |
| `notice_days` | `INT NULL` |  |
| `notes` | `TEXT NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/leases.php](/code/files/includes/leases.php.md):290 | `leaseSave` | update | `tenant_id`, `name`, `reference`, `supplier_id`, `cost_centre_id`, `contract_id`, `notice_days`, `notes`, `updated_datetime` |  |
| [includes/leases.php](/code/files/includes/leases.php.md):296 | `leaseSave` | insert | `tenant_id`, `name`, `reference`, `supplier_id`, `cost_centre_id`, `contract_id`, `notice_days`, `notes`, `created_by`, `created_datetime` |  |
| [includes/leases.php](/code/files/includes/leases.php.md):315 | `leaseDelete` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md) | L22 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseCanView L78, leaseLoad L91, leaseList L339, leaseAssetsAdd L570, leaseHistoryForAsset L747, leaseDueBack L778 ... |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::moveToCompany L666 |  |


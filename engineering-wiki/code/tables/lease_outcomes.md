# lease_outcomes

Leases (3.5.0) - Assets -> Leases; see includes/leases.php What became of a leased asset (Assets -> Settings -> Leasing). kind: 'active' = still on lease, counts towards Due back; 'closed' = finished.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `kind` | `VARCHAR(10) NOT NULL DEFAULT 'active'` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/delete_lease_outcome.php](/code/files/api/assets/delete_lease_outcome.php.md):37 |  | delete |  |  |
| [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md):45 |  | update | `name`, `kind`, `display_order`, `is_active`, `is_default` |  |
| [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md):48 |  | insert | `name`, `kind`, `display_order`, `is_active`, `is_default` |  |
| [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md):53 |  | update | `is_default` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):677 |  | insert | `name`, `kind`, `is_default`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md) | L22 |  |
| [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md) | L38, L42 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L676 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseReady L44, leaseGoneBackSql L146, leaseOutcomes L160, leaseDefaultOutcomeId L169, leaseList L339, leaseGet L419 ... |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::moveToCompany L666 |  |


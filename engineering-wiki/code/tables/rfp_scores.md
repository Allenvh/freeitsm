# rfp_scores

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `supplier_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `consolidated_id` | `INT NOT NULL` |  |
| `score` | `INT NULL` |  |
| `notes` | `LONGTEXT NULL` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/remove_invited_supplier.php](/code/files/api/rfp-builder/remove_invited_supplier.php.md):50 |  | delete |  |  |
| [api/rfp-builder/save_score.php](/code/files/api/rfp-builder/save_score.php.md):67 |  | insert | `rfp_id`, `supplier_id`, `analyst_id`, `consolidated_id`, `score`, `notes` |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):249 | `ContractsService::deleteSupplier` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/get_compare_data.php](/code/files/api/rfp-builder/get_compare_data.php.md) | L79 |  |
| [api/rfp-builder/get_scores.php](/code/files/api/rfp-builder/get_scores.php.md) | L69, L87, L106 |  |


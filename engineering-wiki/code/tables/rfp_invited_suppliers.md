# rfp_invited_suppliers

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `supplier_id` | `INT NOT NULL` |  |
| `invited_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `demo_date` | `DATE NULL` |  |
| `notes` | `LONGTEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/create_prospective_supplier.php](/code/files/api/rfp-builder/create_prospective_supplier.php.md):76 |  | insert | `rfp_id`, `supplier_id`, `demo_date`, `notes` |  |
| [api/rfp-builder/invite_supplier.php](/code/files/api/rfp-builder/invite_supplier.php.md):52 |  | insert | `rfp_id`, `supplier_id`, `demo_date`, `notes` |  |
| [api/rfp-builder/remove_invited_supplier.php](/code/files/api/rfp-builder/remove_invited_supplier.php.md):53 |  | delete |  |  |
| [api/rfp-builder/update_invited_supplier.php](/code/files/api/rfp-builder/update_invited_supplier.php.md):41 |  | update | `demo_date`, `notes` |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):248 | `ContractsService::deleteSupplier` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/get_available_suppliers.php](/code/files/api/rfp-builder/get_available_suppliers.php.md) | L31 |  |
| [api/rfp-builder/get_compare_data.php](/code/files/api/rfp-builder/get_compare_data.php.md) | L67 |  |
| [api/rfp-builder/get_invited_suppliers.php](/code/files/api/rfp-builder/get_invited_suppliers.php.md) | L32 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md) | L35 |  |
| [api/rfp-builder/get_scores.php](/code/files/api/rfp-builder/get_scores.php.md) | L40 |  |
| [api/rfp-builder/invite_supplier.php](/code/files/api/rfp-builder/invite_supplier.php.md) | L47 |  |
| [api/rfp-builder/remove_invited_supplier.php](/code/files/api/rfp-builder/remove_invited_supplier.php.md) | L39 |  |
| [api/rfp-builder/save_score.php](/code/files/api/rfp-builder/save_score.php.md) | L61 |  |
| [api/rfp-builder/update_invited_supplier.php](/code/files/api/rfp-builder/update_invited_supplier.php.md) | L35 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L101 |  |


# supplier_statuses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/contracts/delete_supplier_status.php](/code/files/api/contracts/delete_supplier_status.php.md):34 |  | delete |  |  |
| [api/contracts/save_supplier_status.php](/code/files/api/contracts/save_supplier_status.php.md):35 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/contracts/save_supplier_status.php](/code/files/api/contracts/save_supplier_status.php.md):39 |  | insert | `name`, `description`, `display_order`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/contracts/delete_supplier_status.php](/code/files/api/contracts/delete_supplier_status.php.md) | L33 |  |
| [api/contracts/get_supplier_statuses.php](/code/files/api/contracts/get_supplier_statuses.php.md) | L20 |  |
| [api/contracts/get_suppliers.php](/code/files/api/contracts/get_suppliers.php.md) | L22 |  |
| [api/rfp-builder/create_prospective_supplier.php](/code/files/api/rfp-builder/create_prospective_supplier.php.md) | L55 |  |
| [api/rfp-builder/get_available_suppliers.php](/code/files/api/rfp-builder/get_available_suppliers.php.md) | L31 |  |
| [api/rfp-builder/get_invited_suppliers.php](/code/files/api/rfp-builder/get_invited_suppliers.php.md) | L32 |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) | apiSupplierSelect L157 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleSupplierRows L673, supplierDetail L705 |  |


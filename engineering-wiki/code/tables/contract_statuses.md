# contract_statuses

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
| [api/contracts/delete_contract_status.php](/code/files/api/contracts/delete_contract_status.php.md):34 |  | delete |  |  |
| [api/contracts/save_contract_status.php](/code/files/api/contracts/save_contract_status.php.md):35 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/contracts/save_contract_status.php](/code/files/api/contracts/save_contract_status.php.md):39 |  | insert | `name`, `description`, `display_order`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/contracts/delete_contract_status.php](/code/files/api/contracts/delete_contract_status.php.md) | L33 |  |
| [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md) | L39 |  |
| [api/contracts/get_contract_statuses.php](/code/files/api/contracts/get_contract_statuses.php.md) | L19 |  |
| [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md) | L37 |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) | apiContractSelect L65 |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) | contractsForAsset L111 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleContracts L420, peopleSupplierContracts L823 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L216 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewContract L370 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolSupplierContact L805 |  |


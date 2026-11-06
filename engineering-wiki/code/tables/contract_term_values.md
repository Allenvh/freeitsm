# contract_term_values

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `contract_id` | `INT NOT NULL` |  |
| `term_tab_id` | `INT NOT NULL` |  |
| `content` | `LONGTEXT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/contracts/delete_contract_term_tab.php](/code/files/api/contracts/delete_contract_term_tab.php.md):30 |  | delete |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3941 |  | delete |  |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):111 | `ContractsService::deleteContract` | delete |  |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):155 | `ContractsService::saveTerms` | update | `content`, `updated_datetime` |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):158 | `ContractsService::saveTerms` | insert | `contract_id`, `term_tab_id`, `content`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/contracts/get_contract_terms.php](/code/files/api/contracts/get_contract_terms.php.md) | L24 |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) | apiContractTermsGet L406 |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::saveTerms L151 |  |


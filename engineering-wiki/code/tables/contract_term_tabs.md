# contract_term_tabs

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
| [api/contracts/delete_contract_term_tab.php](/code/files/api/contracts/delete_contract_term_tab.php.md):34 |  | delete |  |  |
| [api/contracts/save_contract_term_tab.php](/code/files/api/contracts/save_contract_term_tab.php.md):35 |  | update | `name`, `description`, `display_order`, `is_active` |  |
| [api/contracts/save_contract_term_tab.php](/code/files/api/contracts/save_contract_term_tab.php.md):39 |  | insert | `name`, `description`, `display_order`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/contracts/delete_contract_term_tab.php](/code/files/api/contracts/delete_contract_term_tab.php.md) | L33 |  |
| [api/contracts/get_contract_term_tabs.php](/code/files/api/contracts/get_contract_term_tabs.php.md) | L19 |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) | apiContractTermsGet L406 |  |


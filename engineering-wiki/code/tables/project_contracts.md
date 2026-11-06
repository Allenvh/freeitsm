# project_contracts

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `contract_id` | `INT NOT NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) | projectBudgetContracts L527 |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::assertMovable L348 |  |


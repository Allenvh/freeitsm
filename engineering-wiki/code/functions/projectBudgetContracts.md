# projectBudgetContracts()

The contracts linked to the project on Connections - what a line may name.

```php
function projectBudgetContracts(PDO $conn, int $projectId): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 524.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 527 | `projectBudgetContracts` | read [project_contracts](/code/tables/project_contracts.md), read [contracts](/code/tables/contracts.md) |  |

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveBudgetLine`


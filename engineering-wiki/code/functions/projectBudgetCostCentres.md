# projectBudgetCostCentres()

Active cost centres of the project's company (NULL company = the Default one).

```php
function projectBudgetCostCentres(PDO $conn, array $project): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 513.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - tenant_id picks the company |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 517 | `projectBudgetCostCentres` | read [cost_centres](/code/tables/cost_centres.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveBudgetLine`


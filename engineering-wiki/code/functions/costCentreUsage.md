# costCentreUsage()

How many things are charged to cost centre `$id`, per kind, leaving out the zeros. Empty means it is free to delete.

```php
function costCentreUsage(PDO $conn, int $id): array
```

Defined in [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), line 158.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | cost_centres.id to count usage of |

**Returns:** array<string,int> e.g. ['asset(s)' => 3, 'person/people' => 1]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 162 | `costCentreUsage` | read [assets](/code/tables/assets.md) |  |
| 163 | `costCentreUsage` | read [contracts](/code/tables/contracts.md) |  |
| 164 | `costCentreUsage` | read [users](/code/tables/users.md) |  |
| 165 | `costCentreUsage` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |

## Called from

- [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) - in `CostCentresService::delete`


# projectAssistRows()

Decorated project rows (health, progress, exceptions, budget totals) for a WHERE, inside `$scope`.

```php
function projectAssistRows(PDO $conn, array $scope, string $where, array $args, int $limit): array
```

Defined in [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `array` |  | [sql, args]: the caller's company filter on alias p, e.g. [' AND p.tenant_id IN (?)', [3]] |
| `$where` | `string` |  | the SQL condition on projects p, with ? placeholders |
| `$args` | `array` |  | the values for `$where`'s placeholders |
| `$limit` | `int` |  | the most rows to return (kept between 1 and 500) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `projectAssistRows` | read [project_stages](/code/tables/project_stages.md) |  |
| 43 | `projectAssistRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectTaskStats()](/code/functions/projectTaskStats.md)

## Called from

- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistFind()](/code/functions/projectAssistFind.md), [projectAssistList()](/code/functions/projectAssistList.md)


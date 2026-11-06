# projectListRows()

The portfolio, scoped to what this analyst may see.

```php
function projectListRows(PDO $conn, int $analystId, array $f = []): array
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 302.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$f` | `array` | `[]` | optional filters: q, status, owner_analyst_id, mine (bool) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 324 | `projectListRows` | read [project_stages](/code/tables/project_stages.md) |  |
| 328 | `projectListRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectPriorityColumn()](/code/functions/projectPriorityColumn.md), [projectProposalApprovalColumn()](/code/functions/projectProposalApprovalColumn.md), [projectStatuses()](/code/functions/projectStatuses.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [projectVisibilityColumn()](/code/functions/projectVisibilityColumn.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md)

## Called from

- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/projects/list.php](/code/files/api/projects/list.php.md)
- [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md)


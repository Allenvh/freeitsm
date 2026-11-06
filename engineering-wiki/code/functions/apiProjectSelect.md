# apiProjectSelect()

The SELECT for projects, with owner and company names, the first active stage and the columns the exceptions are read from.

```php
function apiProjectSelect(PDO $conn): string
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `apiProjectSelect` | read [project_stages](/code/tables/project_stages.md) |  |
| 45 | `apiProjectSelect` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[projectExceptionColumns()](/code/functions/projectExceptionColumns.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiLoadProject()](/code/functions/apiLoadProject.md), [apiProjectsList()](/code/functions/apiProjectsList.md)


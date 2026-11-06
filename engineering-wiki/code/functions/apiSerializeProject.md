# apiSerializeProject()

Shapes a decorated project row for the API - code, names, company, status, health, dates, tools, progress, active stage, exceptions and budget totals.

```php
function apiSerializeProject(PDO $conn, array $p): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$p` | `array` |  | one project row after `apiProjectDecorate()` - with shown_health, progress, tools, exceptions and _budget |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [projectCode()](/code/functions/projectCode.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md), [apiProjectsGet()](/code/functions/apiProjectsGet.md), [apiProjectsCreate()](/code/functions/apiProjectsCreate.md), [apiProjectsUpdate()](/code/functions/apiProjectsUpdate.md)


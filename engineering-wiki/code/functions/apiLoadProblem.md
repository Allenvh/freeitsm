# apiLoadProblem()

Load one problem (with joins) enforcing the key's company scope; 404 if not visible.

```php
function apiLoadProblem(PDO $conn, array $apiKey, int $problemId): array
```

Defined in [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md), line 91.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$problemId` | `int` |  | the problems id |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessProblem()](/code/functions/apiKeyCanAccessProblem.md), [apiProblemSelect()](/code/functions/apiProblemSelect.md)

## Called from

- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsGet()](/code/functions/apiProblemsGet.md), [apiProblemsCreate()](/code/functions/apiProblemsCreate.md), [apiProblemsUpdate()](/code/functions/apiProblemsUpdate.md), [apiProblemNotesList()](/code/functions/apiProblemNotesList.md), [apiProblemAuditList()](/code/functions/apiProblemAuditList.md)


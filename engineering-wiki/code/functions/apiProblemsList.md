# apiProblemsList()

GET /problems

```php
function apiProblemsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md), line 112.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 190 | `apiProblemsList` | read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiProblemSelect()](/code/functions/apiProblemSelect.md), [apiRespond()](/code/functions/apiRespond.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

*Nothing calls this function by name.*


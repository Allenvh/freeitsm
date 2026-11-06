# apiCostCentresList()

GET /cost-centres

```php
function apiCostCentresList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), line 102.

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
| 147 | `apiCostCentresList` | read [cost_centres](/code/tables/cost_centres.md) |  |

## Calls

[apiCostCentreCompany()](/code/functions/apiCostCentreCompany.md), [apiCostCentreSelect()](/code/functions/apiCostCentreSelect.md), [apiCostCentresReady()](/code/functions/apiCostCentresReady.md), [apiError()](/code/functions/apiError.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


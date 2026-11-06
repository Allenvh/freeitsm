# apiContractsList()

GET /contracts

```php
function apiContractsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 225.

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
| 324 | `apiContractsList` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md) |  |

## Calls

[apiContractSelect()](/code/functions/apiContractSelect.md), [apiContractVisibility()](/code/functions/apiContractVisibility.md), [apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDateOnly()](/code/functions/apiParseDateOnly.md), [apiRespond()](/code/functions/apiRespond.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [naive_today_sql()](/code/functions/naive_today_sql.md)

*Nothing calls this function by name.*


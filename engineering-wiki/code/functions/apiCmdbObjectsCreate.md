# apiCmdbObjectsCreate()

POST /cmdb/objects

```php
function apiCmdbObjectsCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), line 416.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiCmdbLoadObject()](/code/functions/apiCmdbLoadObject.md), [apiCmdbSerializeObject()](/code/functions/apiCmdbSerializeObject.md), [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


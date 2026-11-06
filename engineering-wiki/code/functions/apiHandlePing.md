# apiHandlePing()

GET /ping — auth check + what this key can do.

```php
function apiHandlePing(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/index.php](/code/files/api/v1/index.php.md), line 194.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiRespond()](/code/functions/apiRespond.md), [getTenantById()](/code/functions/getTenantById.md)

*Nothing calls this function by name.*


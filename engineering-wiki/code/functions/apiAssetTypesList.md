# apiAssetTypesList()

Reference lookups The reference lists resolve for the key's default company (globals + that company's own; every row at N=1). Suppliers stay a shared registry.

```php
function apiAssetTypesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 649.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiRespond()](/code/functions/apiRespond.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

*Nothing calls this function by name.*


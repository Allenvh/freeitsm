# apiStatusServicesList()

GET /service-status/services — the health board (derived current_status inline)

```php
function apiStatusServicesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 197.

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
| 213 | `apiStatusServicesList` | read [status_services](/code/tables/status_services.md) |  |

## Calls

[apiRespond()](/code/functions/apiRespond.md), [apiSerializeService()](/code/functions/apiSerializeService.md), [apiServiceStatusSubquery()](/code/functions/apiServiceStatusSubquery.md)

*Nothing calls this function by name.*


# apiStatusIncidentsDelete()

DELETE /service-status/incidents/{id} - deletes an incident through ServiceStatusService.

```php
function apiStatusIncidentsDelete(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), line 350.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiRespond()](/code/functions/apiRespond.md), [apiServiceStatusFail()](/code/functions/apiServiceStatusFail.md)

*Nothing calls this function by name.*


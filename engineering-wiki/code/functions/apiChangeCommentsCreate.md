# apiChangeCommentsCreate()

POST /changes/{id}/comments - adds a comment to the change through ChangesService.

```php
function apiChangeCommentsCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 405.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiFailFromService()](/code/functions/apiFailFromService.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


# apiMorningChecksBoard()

GET /morning-checks/board?date=YYYY-MM-DD — get_todays_checks.php's view: every active check with its result for the date (or null if not yet done).

```php
function apiMorningChecksBoard(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), line 196.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiMorningCheckDate()](/code/functions/apiMorningCheckDate.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


# apiMorningCheckResultsRecord()

POST /morning-checks/results — save_check_result.php's upsert: one result per check per date. 201 on first record of the day, 200 on overwrite.

```php
function apiMorningCheckResultsRecord(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), line 304.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiFailFromService()](/code/functions/apiFailFromService.md), [apiLoadMorningResult()](/code/functions/apiLoadMorningResult.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeMorningResult()](/code/functions/apiSerializeMorningResult.md)

*Nothing calls this function by name.*


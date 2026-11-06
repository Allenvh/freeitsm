# apiTaskStatusesList()

Reference lookups

```php
function apiTaskStatusesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), line 455.

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
| 457 | `apiTaskStatusesList` | read [task_statuses](/code/tables/task_statuses.md) |  |

## Calls

[apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


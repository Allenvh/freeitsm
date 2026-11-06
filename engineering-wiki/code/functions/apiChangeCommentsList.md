# apiChangeCommentsList()

Comments — append + delete (no edit), always internal, like the UI

```php
function apiChangeCommentsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 386.

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
| 389 | `apiChangeCommentsList` | read [change_comments](/code/tables/change_comments.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadChange()](/code/functions/apiLoadChange.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


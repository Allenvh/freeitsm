# apiProjectsDelete()

DELETE /projects/{id} - its tasks are detached, never deleted

```php
function apiProjectsDelete(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 279.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiLoadProject()](/code/functions/apiLoadProject.md), [apiProjectCtx()](/code/functions/apiProjectCtx.md), [apiProjectTry()](/code/functions/apiProjectTry.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


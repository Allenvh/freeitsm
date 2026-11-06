# apiTasksList()

GET /tasks

```php
function apiTasksList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), line 160.

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
| 212 | `apiTasksList` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 216 | `apiTasksList` | read [task_collaborators](/code/tables/task_collaborators.md) |  |
| 231 | `apiTasksList` | read [task_tag_map](/code/tables/task_tag_map.md), read [task_tags](/code/tables/task_tags.md) |  |
| 277 | `apiTasksList` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDateOnly()](/code/functions/apiParseDateOnly.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeTask()](/code/functions/apiSerializeTask.md), [apiTaskSelect()](/code/functions/apiTaskSelect.md), [naive_today_sql()](/code/functions/naive_today_sql.md)

*Nothing calls this function by name.*


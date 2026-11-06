# apiLoadTask()

Loads one task row, or answers 404 and stops - also for a task in a company the key cannot reach, when the key is given.

```php
function apiLoadTask(PDO $conn, int $taskId, ?array $apiKey = null): array
```

Defined in [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), line 138.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `?array` | `null` | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiTaskSelect()](/code/functions/apiTaskSelect.md)

## Called from

- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksGet()](/code/functions/apiTasksGet.md), [apiTasksCreate()](/code/functions/apiTasksCreate.md), [apiTasksUpdate()](/code/functions/apiTasksUpdate.md), [apiTasksMove()](/code/functions/apiTasksMove.md), [apiTaskCommentsList()](/code/functions/apiTaskCommentsList.md)


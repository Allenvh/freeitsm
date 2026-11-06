# apiTaskContractor()

Contractors (3.3.0): the supplier and the person there, by name - includes/task_contractors.php.

```php
function apiTaskContractor(PDO $conn, int $taskId): ?array
```

Defined in [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), line 57.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Calls

[tasksContractors()](/code/functions/tasksContractors.md)

## Called from

- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiSerializeTask()](/code/functions/apiSerializeTask.md)


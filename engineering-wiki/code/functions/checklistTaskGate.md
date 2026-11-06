# checklistTaskGate()

Refuse unless this analyst can reach the task. Same wording for "gone" and "not yours".

```php
function checklistTaskGate(PDO $conn, int $taskId): void
```

Defined in [api/tasks/checklists.php](/code/files/api/tasks/checklists.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[analystCanAccessTask()](/code/functions/analystCanAccessTask.md)

## Called from

- [api/tasks/checklists.php](/code/files/api/tasks/checklists.php.md)


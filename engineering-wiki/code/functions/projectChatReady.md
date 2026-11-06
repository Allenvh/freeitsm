# projectChatReady()

Have Database Verification's tables arrived?

```php
function projectChatReady(PDO $conn): bool
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `projectChatReady` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 70 | `projectChatReady` | read [project_ai_threads](/code/tables/project_ai_threads.md) |  |

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md)


# projectChatOpen()

Open the panel: a greeting for an empty conversation, a catch-up after a while, or nothing.

```php
function projectChatOpen(PDO $conn, ActorContext $ctx, array $project): array
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 441.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row the panel is open on |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 445 | `projectChatOpen` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |

## Calls

[projectChatMaturity()](/code/functions/projectChatMaturity.md), [projectChatMessages()](/code/functions/projectChatMessages.md), [projectChatThread()](/code/functions/projectChatThread.md), [projectChatTurn()](/code/functions/projectChatTurn.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)


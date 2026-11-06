# projectChatClear()

Start again: the conversation and its memory go. A shared one needs somebody who may change the project.

```php
function projectChatClear(PDO $conn, ActorContext $ctx, array $project): void
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 654.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row whose conversation is cleared |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 661 | `projectChatClear` | **delete** [project_ai_threads](/code/tables/project_ai_threads.md) |  |

## Calls

[projectCanChange()](/code/functions/projectCanChange.md), [projectChatThread()](/code/functions/projectChatThread.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)


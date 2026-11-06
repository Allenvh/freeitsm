# projectChatTurn()

One turn. `$mode`: 'chat' (the person wrote `$text`), 'open' (a greeting for an empty conversation) or 'resume' (a catch-up after a while). Stores the turn and returns the new messages. Throws RuntimeException for provider problems ('not_configured' when AI is not set up).

```php
function projectChatTurn(
    PDO $conn,
    ActorContext $ctx,
    array $project,
    string $mode,
    string $text = ''
): array
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 348.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row the conversation is about |
| `$mode` | `string` |  | 'chat' (the person wrote `$text`), 'open' (a first greeting) or 'resume' (a catch-up) |
| `$text` | `string` | `''` | what the person wrote; only used in 'chat' mode, cut to PROJECT_CHAT_MAX_INPUT characters |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 363 | `projectChatTurn` | read [project_ai_messages](/code/tables/project_ai_messages.md) |  |
| 392 | `projectChatTurn` | read [suppliers](/code/tables/suppliers.md) |  |
| 395 | `projectChatTurn` | read [contacts](/code/tables/contacts.md) |  |
| 400 | `projectChatTurn` | read [tasks](/code/tables/tasks.md) |  |
| 408 | `projectChatTurn` | read [tasks](/code/tables/tasks.md) |  |
| 408 | `projectChatTurn` | read [analysts](/code/tables/analysts.md) |  |
| 426 | `projectChatTurn` | **insert** [project_ai_messages](/code/tables/project_ai_messages.md) (`thread_id`, `role`, `kind`, `content`, `proposals`, `looked_at`, `analyst_id`, `model`, `tokens_in`, `tokens_out`, `created_datetime`) |  |
| 431 | `projectChatTurn` | **update** [project_ai_threads](/code/tables/project_ai_threads.md) (`updated_datetime`) |  |

## Calls

[aiProviderChatTools()](/code/functions/aiProviderChatTools.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectChatDescribe()](/code/functions/projectChatDescribe.md), [projectChatMaturity()](/code/functions/projectChatMaturity.md), [projectChatMessages()](/code/functions/projectChatMessages.md), [projectChatRead()](/code/functions/projectChatRead.md), [projectChatReady()](/code/functions/projectChatReady.md), [projectChatSummarise()](/code/functions/projectChatSummarise.md), [projectChatSystem()](/code/functions/projectChatSystem.md), [projectChatThread()](/code/functions/projectChatThread.md), [projectChatTools()](/code/functions/projectChatTools.md), [t()](/code/functions/t.md), [tasksContractorValidate()](/code/functions/tasksContractorValidate.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatOpen()](/code/functions/projectChatOpen.md)


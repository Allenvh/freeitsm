# projectChatSystem()

The standing instructions, the set-up checklist, the memory and the project, for one turn.

```php
function projectChatSystem(
    PDO $conn,
    array $project,
    int $analystId,
    array $maturity,
    ?string $summary,
    bool $canChange
): string
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 163.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row the conversation is about |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$maturity` | `array` |  | the set-up checklist from `projectChatMaturity()` |
| `$summary` | `?string` |  | the thread's memory (project_ai_threads.summary); null when nothing has been folded in yet |
| `$canChange` | `bool` |  | whether the analyst may change the project - decides whether propose_* tools are offered |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 165 | `projectChatSystem` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectAiFacts()](/code/functions/projectAiFacts.md), [projectAiLanguage()](/code/functions/projectAiLanguage.md), [projectEnabledTools()](/code/functions/projectEnabledTools.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md)

## Called from

- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md)


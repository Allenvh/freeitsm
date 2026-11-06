# projectAiAsk()

Ask the model. Returns ['text', 'model']. Throws RuntimeException when AI is not set up ('not_configured') or the provider cannot be reached.

```php
function projectAiAsk(PDO $conn, string $task, string $facts, int $maxTokens): array
```

Defined in [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), line 347.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$task` | `string` |  | the instruction for the model, e.g. `projectAiBriefingTask()` or a report kind's wording |
| `$facts` | `string` |  | the project facts from `projectAiFacts()`, wrapped in <project_data> for the model |
| `$maxTokens` | `int` |  | the most tokens the reply may use |

**Returns:** `array`

## Calls

[aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [projectAiSystemPrompt()](/code/functions/projectAiSystemPrompt.md)

## Called from

- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::briefing`, `ProjectReportsService::draftWithAi`, `ProjectReportsService::draftScheduled`


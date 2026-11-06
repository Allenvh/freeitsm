# loadWorkflowAiConfig()

Load the workflow-specific AI settings from `system_settings`. Throws if no key is configured so the caller can surface "configure your AI provider under Workflow → Settings → AI Integration first" to the user.

```php
function loadWorkflowAiConfig(PDO $conn): array
```

Defined in [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

## Called from

- [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md)


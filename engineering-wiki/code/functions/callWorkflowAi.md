# callWorkflowAi()

Provider-agnostic one-shot call via the shared client (Anthropic / OpenAI / OpenRouter). Returns the assistant's response text (a plain string).

```php
function callWorkflowAi(
    array $cfg,
    string $systemPrompt,
    string $userMessage,
    int $maxTokens = 2500
): string
```

Defined in [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the workflow AI settings from `loadWorkflowAiConfig()` - provider, model, api_key, verify_ssl |
| `$systemPrompt` | `string` |  | the system prompt sent ahead of the user message |
| `$userMessage` | `string` |  | the single user message to answer |
| `$maxTokens` | `int` | `2500` | the most tokens the reply may use |

**Returns:** `string`

## Calls

[aiProviderChat()](/code/functions/aiProviderChat.md)

## Called from

- [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md)


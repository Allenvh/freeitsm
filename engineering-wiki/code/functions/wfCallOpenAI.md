# wfCallOpenAI()

Sends a system prompt and one user message to the OpenAI Chat Completions API and returns the reply text, trimmed.

Throws on a network error or a non-200 answer, with the provider's own message where
it gave one.

```php
function wfCallOpenAI(array $cfg, string $systemPrompt, string $userMessage, int $maxTokens): string
```

Defined in [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md), line 183.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the AI settings - model, api_key and verify_ssl are read |
| `$systemPrompt` | `string` |  | the system prompt, sent as the first "system" chat message |
| `$userMessage` | `string` |  | the single user message to answer |
| `$maxTokens` | `int` |  | the most tokens the reply may use |

**Returns:** `string`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md), [workflowEffectiveSslVerify()](/code/functions/workflowEffectiveSslVerify.md)

*Nothing calls this function by name.*


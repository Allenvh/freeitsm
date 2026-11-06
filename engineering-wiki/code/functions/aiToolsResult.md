# aiToolsResult()

Shared return shape for aiProviderChatTools.

```php
function aiToolsResult(
    string $content,
    array $calls,
    int $tokIn,
    int $tokOut,
    string $provider,
    string $model,
    float $start
): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 701.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$content` | `string` |  | the model's final answer text; '' when the round limit ran out |
| `$calls` | `array` |  | every tool call made, as [['name','args','result'], ...] |
| `$tokIn` | `int` |  | input tokens summed over every round |
| `$tokOut` | `int` |  | output tokens summed over every round |
| `$provider` | `string` |  | the provider used (anthropic, openai, openrouter or azure) |
| `$model` | `string` |  | the model id, or the Azure deployment name |
| `$start` | `float` |  | microtime(true) when the conversation began, for duration_ms |

**Returns:** `array`

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderChatTools()](/code/functions/aiProviderChatTools.md)


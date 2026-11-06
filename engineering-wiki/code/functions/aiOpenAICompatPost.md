# aiOpenAICompatPost()

POST an OpenAI-format chat request, putting right the two parameters newer models refuse by name.

⚠️ `max_tokens` VS `max_completion_tokens`. Newer deployments — the o-series
and after — refuse `max_tokens` outright and say so by name. The two mean the
same thing here, and which one is required depends on the deployed model
rather than on anything we can see from the config, so the only honest way to
know is to be told.

⚠️ `temperature`. The same models (GH #171: gpt-5-nano) accept only their
default temperature and refuse 0 with "Unsupported value: 'temperature'".
Leaving it out means "the model's default", which is all they allow.

Each fix is applied at most once, and only when the provider's own words name
it — so this cannot loop, and it cannot fire on an unrelated 400 such as a bad
key or a content filter. `$payload` is passed by reference so a caller making
several calls (tool calling) learns the fix once rather than per round.

```php
function aiOpenAICompatPost(string $url, array $headers, array &$payload, bool $verify): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 373.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full chat-completions URL to POST to |
| `$headers` | `array` |  | every header line to send, auth and content-type included |
| `$payload` | `array` |  | the request body before JSON encoding; by reference, so a corrected field stays corrected |
| `$verify` | `bool` |  | the feature's verify-SSL setting, passed on to `aiProviderHttpPost()` |

**Returns:** `array`

## Calls

[aiProviderHttpPost()](/code/functions/aiProviderHttpPost.md)

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderCallOpenAICompatible()](/code/functions/aiProviderCallOpenAICompatible.md), [aiProviderChatTools()](/code/functions/aiProviderChatTools.md)


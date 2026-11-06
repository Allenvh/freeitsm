# aiProviderCallAnthropic()

Sends one system + user prompt to the Anthropic Messages API and returns the joined text blocks with token counts and the stop reason.

```php
function aiProviderCallAnthropic(string $model, string $apiKey, bool $verify, array $opts): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 160.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$model` | `string` |  | the Anthropic model id, already trimmed and non-empty |
| `$apiKey` | `string` |  | the plaintext API key, sent as x-api-key |
| `$verify` | `bool` |  | the feature's verify-SSL setting, passed on to `aiProviderHttpPost()` |
| `$opts` | `array` |  | `aiProviderChat()`'s options with max_tokens and temperature filled in; system and user are read |

**Returns:** array  {content, tokens_in, tokens_out, finish_reason, reasoning_tokens (always null here)}

## Calls

[aiProviderHttpPost()](/code/functions/aiProviderHttpPost.md)

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderChat()](/code/functions/aiProviderChat.md)


# aiProviderHttpPost()

POST with retry/backoff on 429 / 5xx / network errors. Ported from rfpAiHttpPostWithRetry so this file stands alone.

```php
function aiProviderHttpPost(string $url, array $headers, string $body, bool $verifySsl): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 723.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full URL to POST to |
| `$headers` | `array` |  | every header line to send |
| `$body` | `string` |  | the request body, already JSON-encoded |
| `$verifySsl` | `bool` |  | the feature's verify-SSL setting; not applied - `sslApplyCurl()` uses the install-wide setting |

**Returns:** `array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderCallAnthropic()](/code/functions/aiProviderCallAnthropic.md), [aiOpenAICompatPost()](/code/functions/aiOpenAICompatPost.md), [aiProviderChatTools()](/code/functions/aiProviderChatTools.md)


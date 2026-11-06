# rfpAiCallAnthropicStreaming()

Streaming Anthropic call. Receives token deltas as they arrive and forwards them to a caller-supplied callback so the browser can show live output (claude.ai-style) instead of waiting for the whole response. Single attempt, no retry — once we've started streaming to the browser we can't replay from the start.

The callback signature is fn(string `$eventType`, array `$data`): void
where `$eventType` is one of:
  'text'   — { delta: string }    per-token text chunk
  'usage'  — { tokens_in, tokens_out, cache_read, cache_write }

Returns:
  content     string  Full accumulated text
  tokens_in   int|null
  tokens_out  int|null
  cache_read  int|null
  cache_write int|null
  provider, model, duration_ms

Throws on non-2xx response or stream-level errors. The caller
should catch and emit its own error event to the browser.

Only Anthropic supported today — OpenAI streaming has a different
SSE shape and callers should pre-check the configured provider.

```php
function rfpAiCallAnthropicStreaming(
    PDO $conn,
    array $opts,
    callable $onEvent,
    ?array $settingsOverride = null
): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 266.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` |  | system, user (the prompts), max_tokens (default 4096), temperature (default 0.0) |
| `$onEvent` | `callable` |  | fn(string `$eventType`, array `$data`) - called with 'text' deltas and 'usage' updates as they arrive |
| `$settingsOverride` | `?array` | `null` | another feature's own settings (provider, api_key, model) to bill to; null uses the RFP AI settings |

**Returns:** `array`

## Calls

[rfpAiGetSettings()](/code/functions/rfpAiGetSettings.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md)
- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)
- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md)
- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiConsolidateStreaming()](/code/functions/rfpAiConsolidateStreaming.md), [rfpAiGenerateSectionStreaming()](/code/functions/rfpAiGenerateSectionStreaming.md), [rfpAiGenerateFramingStreaming()](/code/functions/rfpAiGenerateFramingStreaming.md), [rfpAiRestyleStreaming()](/code/functions/rfpAiRestyleStreaming.md)


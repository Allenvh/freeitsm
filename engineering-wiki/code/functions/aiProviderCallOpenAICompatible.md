# aiProviderCallOpenAICompatible()

The OpenAI chat-completions wire format, at whatever URL and behind whatever auth header the caller names.

⚠️ It takes a FULL URL and an AUTH HEADER LIST rather than a base and a key,
because Azure differs in exactly those two places (a required query string,
and `api-key:` instead of `Authorization: Bearer`). Passing them in keeps one
copy of the body-building, response-parsing and reasoning-token handling
below — all of which Azure shares — instead of a near-identical second one
that would drift.

An empty `$model` omits the field entirely, which is what a deployment-based
endpoint wants.

```php
function aiProviderCallOpenAICompatible(
    string $url,
    string $model,
    array $authHeaders,
    bool $verify,
    array $opts,
    array $extraHeaders = []
): array
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 291.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full chat-completions URL, including Azure's api-version query string where needed |
| `$model` | `string` |  | the model id to send; '' leaves the model field out (an Azure deployment-based endpoint) |
| `$authHeaders` | `array` |  | the auth header lines - 'Authorization: Bearer ...' or Azure's 'api-key: ...' |
| `$verify` | `bool` |  | the feature's verify-SSL setting, passed on to `aiProviderHttpPost()` |
| `$opts` | `array` |  | `aiProviderChat()`'s options with max_tokens and temperature filled in; system, user, reasoning read |
| `$extraHeaders` | `array` | `[]` | further header lines, such as OpenRouter's HTTP-Referer and X-Title; [] for none |

**Returns:** `array`

## Calls

[aiOpenAICompatPost()](/code/functions/aiOpenAICompatPost.md)

## Called from

- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiProviderChat()](/code/functions/aiProviderChat.md)


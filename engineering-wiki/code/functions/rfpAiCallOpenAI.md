# rfpAiCallOpenAI()

Sends one prompt to the OpenAI chat completions API and returns the reply text with token counts.

```php
function rfpAiCallOpenAI(array $settings, array $opts): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 205.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$settings` | `array` |  | the provider settings from `rfpAiGetSettings()`: model, api_key, verify_ssl |
| `$opts` | `array` |  | system, user, max_tokens, temperature (defaults already filled in by `rfpAiCall()`) |

**Returns:** `array`

## Calls

[rfpAiHttpPostWithRetry()](/code/functions/rfpAiHttpPostWithRetry.md)

## Called from

- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiCall()](/code/functions/rfpAiCall.md)


# formsTestOpenAI()

Sends a tiny prompt to the OpenAI Chat Completions API to prove the key and model work, and returns the reply text with the token counts.

Throws with the provider's own error message (or the HTTP code) when the call fails.

```php
function formsTestOpenAI(string $apiKey, string $model, bool $verifySsl): array
```

Defined in [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md), line 140.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$apiKey` | `string` |  | the OpenAI API key to test, in plain text |
| `$model` | `string` |  | the model id to call, as chosen in the form's AI settings |
| `$verifySsl` | `bool` |  | the "verify SSL" tick from the request - unused: `sslApplyCurl()` follows the global switch alone |

**Returns:** `array`

## Calls

[formsEffectiveSslVerify()](/code/functions/formsEffectiveSslVerify.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md)


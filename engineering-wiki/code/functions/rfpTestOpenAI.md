# rfpTestOpenAI()

Sends a tiny prompt to the OpenAI Chat Completions API to prove the key and model work, and returns the reply text with the token counts.

Throws with the provider's own error message (or the HTTP code) when the call fails.

```php
function rfpTestOpenAI(string $apiKey, string $model, bool $verifySsl): array
```

Defined in [api/rfp-builder/test_ai_connection.php](/code/files/api/rfp-builder/test_ai_connection.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$apiKey` | `string` |  | the OpenAI API key to test, in plain text |
| `$model` | `string` |  | the model id to call |
| `$verifySsl` | `bool` |  | the "verify SSL" tick from the request - unused: `sslApplyCurl()` follows the global switch alone |

**Returns:** `array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/rfp-builder/test_ai_connection.php](/code/files/api/rfp-builder/test_ai_connection.php.md)


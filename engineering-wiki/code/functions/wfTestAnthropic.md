# wfTestAnthropic()

Sends a tiny prompt to the Anthropic Messages API to prove the key and model work, and returns the reply text with the token counts.

Throws with the provider's own error message (or the HTTP code) when the call fails.

```php
function wfTestAnthropic(string $apiKey, string $model, bool $verifySsl): array
```

Defined in [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$apiKey` | `string` |  | the plaintext key to test - typed in the form, or the decrypted saved key |
| `$model` | `string` |  | the model id typed in the form, already trimmed and non-empty |
| `$verifySsl` | `bool` |  | the form's verify-SSL tick; combined with SSL_VERIFY_PEER but not applied to the handle |

**Returns:** `array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md), [workflowEffectiveSslVerify()](/code/functions/workflowEffectiveSslVerify.md)

## Called from

- [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md)


# aiExplainFailure()

What a failed call most likely means, in a sentence an administrator can act on - or '' when the error says it plainly enough already.

Shared by the Test button and debug tool D014, so the two never disagree.

```php
function aiExplainFailure(array $cfg, string $msg): string
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 426.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the AI settings the call used - provider, model, azure_deployment and azure_api_version are read |
| `$msg` | `string` |  | the failure's message, as thrown by `aiProviderHttpPost()` (includes "(HTTP nnn)") |

**Returns:** `string`

## Calls

[aiAzureApiVersionLooksValid()](/code/functions/aiAzureApiVersionLooksValid.md)

## Called from

- [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md)
- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)


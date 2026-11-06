# rfpAiGetSettings()

Load the configured provider, model, decrypted API key and SSL-verify flag. Throws if any required piece is missing — callers surface that as a user-friendly "configure under Contracts → Settings → RFP AI" message.

```php
function rfpAiGetSettings(PDO $conn): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 | `rfpAiGetSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md)

## Called from

- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiCall()](/code/functions/rfpAiCall.md), [rfpAiCallAnthropicStreaming()](/code/functions/rfpAiCallAnthropicStreaming.md)


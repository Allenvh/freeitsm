# loadFormsAiConfig()

Load `forms_ai_*` settings. Throws if no key is saved so callers can surface a clear "configure AI under Forms → Settings → AI" message.

Returns a dict with the four fields the streaming helper expects:
provider, model, api_key (decrypted), verify_ssl.

```php
function loadFormsAiConfig(PDO $conn): array
```

Defined in [api/forms/_ai_helpers.php](/code/files/api/forms/_ai_helpers.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

## Called from

- [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md)


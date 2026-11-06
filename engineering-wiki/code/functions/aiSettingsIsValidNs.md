# aiSettingsIsValidNs()

True when `$ns` is a namespace registered in `aiSettingsRegistry()`.

```php
function aiSettingsIsValidNs(string $ns): bool
```

Defined in [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), line 106.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ns` | `string` |  | the namespace to check, possibly from the request (e.g. 'knowledge_ai') |

**Returns:** `bool`

## Calls

[aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md)

## Called from

- [api/system/ai/get_settings.php](/code/files/api/system/ai/get_settings.php.md)
- [api/system/ai/save_settings.php](/code/files/api/system/ai/save_settings.php.md)
- [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md)


# aiSettingsKeys()

The system_settings keys for a namespace.

```php
function aiSettingsKeys(string $ns): array
```

Defined in [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ns` | `string` |  | the namespace, used as the prefix of every key; not checked against the registry here |

**Returns:** `array`

## Called from

- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [aiSettingsSave()](/code/functions/aiSettingsSave.md)


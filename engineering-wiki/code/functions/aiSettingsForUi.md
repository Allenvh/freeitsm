# aiSettingsForUi()

UI-safe view of the config — never returns the plaintext key, only a mask and a has_key flag.

```php
function aiSettingsForUi(PDO $conn, string $ns): array
```

Defined in [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), line 222.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ns` | `string` |  | a registered namespace, e.g. 'workflow_ai'; an unknown one throws |

**Returns:** `array`

## Calls

[aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [maskSecret()](/code/functions/maskSecret.md)

## Called from

- [api/system/ai/get_settings.php](/code/files/api/system/ai/get_settings.php.md)


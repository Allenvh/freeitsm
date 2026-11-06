# requireAiNamespaceJson()

The API twin: refuse with a 403 unless this analyst may manage the namespace's AI config.

```php
function requireAiNamespaceJson(PDO $conn, string $ns): void
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 253.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ns` | `string` |  | the AI settings namespace, e.g. 'knowledge_ai'; checked for the signed-in analyst |

**Returns:** `void`

## Calls

[analystCanManageAiNamespace()](/code/functions/analystCanManageAiNamespace.md)

## Called from

- [api/system/ai/get_settings.php](/code/files/api/system/ai/get_settings.php.md)
- [api/system/ai/save_settings.php](/code/files/api/system/ai/save_settings.php.md)
- [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md)


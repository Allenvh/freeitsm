# setupKey()

The address and key of a setup, so features sharing one are called once.

```php
function setupKey(array $cfg): string
```

Defined in [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | one feature's AI settings: provider, model, api_key, verify_ssl and the azure_* fields |

**Returns:** `string`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)


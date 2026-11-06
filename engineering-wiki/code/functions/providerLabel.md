# providerLabel()

The display name of an AI provider key (anthropic, openai, openrouter, azure), or the key itself when it is not one of those.

```php
function providerLabel(string $p): string
```

Defined in [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), line 157.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `string` |  | the provider key, e.g. 'anthropic' or 'azure' |

**Returns:** `string`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)


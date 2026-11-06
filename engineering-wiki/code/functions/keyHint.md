# keyHint()

First four characters and a length — enough to tell a wrong key from no key.

```php
function keyHint(string $k): string
```

Defined in [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$k` | `string` |  | an API key, in plain text; empty gives (none saved) |

**Returns:** `string`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md) - in [redactKey()](/code/functions/redactKey.md)


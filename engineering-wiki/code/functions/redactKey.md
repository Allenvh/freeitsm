# redactKey()

Replace the key wherever it appears with its hint.

```php
function redactKey(string $s, string $key): string
```

Defined in [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | text that may contain the key, such as a URL, header or body |
| `$key` | `string` |  | the API key to hide; empty leaves `$s` alone |

**Returns:** `string`

## Calls

[keyHint()](/code/functions/keyHint.md)

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md) - in [traceLines()](/code/functions/traceLines.md)


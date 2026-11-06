# indent()

Indents every line of a block of text by `$pad`.

```php
function indent(string $s, string $pad = ' '): string
```

Defined in [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), line 114.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | the text to indent |
| `$pad` | `string` | `' '` | what to put before each line |

**Returns:** `string`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md) - in [traceLines()](/code/functions/traceLines.md)


# traceLines()

Print one recorded HTTP exchange.

```php
function traceLines(array $t, string $key, int $n): array
```

Defined in [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), line 124.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `array` |  | one recorded exchange from `aiProviderTraceTake()`: attempt, url, headers, body, response, code, ms, error |
| `$key` | `string` |  | the API key to redact from everything printed |
| `$n` | `int` |  | the exchange's number in the report, from 1 |

**Returns:** `array`

## Calls

[indent()](/code/functions/indent.md), [prettyBody()](/code/functions/prettyBody.md), [redactKey()](/code/functions/redactKey.md)

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)


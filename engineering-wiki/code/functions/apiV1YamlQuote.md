# apiV1YamlQuote()

Double-quotes a string for YAML, escaping backslashes, quotes, newlines, tabs and carriage returns.

```php
function apiV1YamlQuote(string $s): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 504.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | the string to quote |

**Returns:** `string`

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1YamlKey()](/code/functions/apiV1YamlKey.md), [apiV1YamlScalar()](/code/functions/apiV1YamlScalar.md)


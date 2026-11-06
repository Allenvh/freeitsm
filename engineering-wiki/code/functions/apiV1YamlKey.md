# apiV1YamlKey()

A mapping key as YAML - plain when it is a simple identifier or path, double-quoted otherwise.

```php
function apiV1YamlKey(string $k): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 473.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$k` | `string` |  | a mapping key |

**Returns:** `string`

## Calls

[apiV1YamlQuote()](/code/functions/apiV1YamlQuote.md)

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1YamlNode()](/code/functions/apiV1YamlNode.md)


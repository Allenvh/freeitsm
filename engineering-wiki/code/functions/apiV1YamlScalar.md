# apiV1YamlScalar()

A scalar as YAML - null, true, false and numbers as they are, a string plain only when it is unambiguous text and double-quoted otherwise, so nothing is read back as another type.

```php
function apiV1YamlScalar($v): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 484.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | a null, boolean, number or string |

**Returns:** `string`

## Calls

[apiV1YamlQuote()](/code/functions/apiV1YamlQuote.md)

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1YamlNode()](/code/functions/apiV1YamlNode.md)


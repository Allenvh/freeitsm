# apiV1YamlNode()

Renders one value as YAML at the given indent - lists as "- " items with the first key of a mapping hung on the dash, mappings as "key: value", empty arrays as [] or {}.

```php
function apiV1YamlNode($value, int $indent): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 432.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the value to render - an array (list or mapping) or a scalar |
| `$indent` | `int` |  | the nesting level; each level is two spaces |

**Returns:** `string`

## Calls

[apiV1YamlKey()](/code/functions/apiV1YamlKey.md), [apiV1YamlScalar()](/code/functions/apiV1YamlScalar.md)

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1OpenApiToYaml()](/code/functions/apiV1OpenApiToYaml.md), [apiV1YamlNode()](/code/functions/apiV1YamlNode.md)


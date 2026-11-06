# apiV1OpenApiToYaml()

Renders the OpenAPI document as YAML, with a generated-file header line.

```php
function apiV1OpenApiToYaml(array $data): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 421.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` | `array` |  | the OpenAPI document as built by `apiV1BuildOpenApi()` |

**Returns:** `string`

## Calls

[apiV1YamlNode()](/code/functions/apiV1YamlNode.md)

## Called from

- [api/v1/openapi.php](/code/files/api/v1/openapi.php.md)


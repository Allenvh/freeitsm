# oav_resolveRef()

Follows a schema's `$ref` chain into the component schemas and returns the schema it ends on (an empty array when a name is missing).

```php
function oav_resolveRef($schema, $components)
```

Defined in [api/v1/dev/openapi_verify.php](/code/files/api/v1/dev/openapi_verify.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$schema` |  |  | a schema that may be a `$ref` to a component schema |
| `$components` |  |  | the component schemas, keyed by name |

## Called from

- [api/v1/dev/openapi_verify.php](/code/files/api/v1/dev/openapi_verify.php.md) - in [oav_validate()](/code/functions/oav_validate.md)


# apiV1ParamDescription()

Pull a parameter description from the docs params for a given name/in.

```php
function apiV1ParamDescription(array $ep, string $name, string $in): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 340.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ep` | `array` |  | one endpoint from spec.json: m (method), p (path template), s, d, params, perm |
| `$name` | `string` |  | the parameter's name |
| `$in` | `string` |  | where it goes: 'path' or 'query' |

**Returns:** `string`

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOperation()](/code/functions/apiV1BuildOperation.md)


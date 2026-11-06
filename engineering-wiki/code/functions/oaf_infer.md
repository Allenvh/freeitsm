# oaf_infer()

Builds an OpenAPI schema from a live value - its type, nested properties or list items, and nullable for nulls, strings and objects, which can be empty in other rows.

```php
function oaf_infer($v)
```

Defined in [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | a value from a live API response |

## Called from

- [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md) - in [oaf_infer()](/code/functions/oaf_infer.md), [oaf_patch()](/code/functions/oaf_patch.md)


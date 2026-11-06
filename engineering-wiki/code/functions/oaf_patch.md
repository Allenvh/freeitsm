# oaf_patch()

Brings a schema node into line with one live value, in place: follows `$ref` into the shared component schemas, marks nullable where the value is null, types a typeless node, adds missing object properties and drops required fields the response does not have.

Counts every change in the global `$patches`, so the script can say whether anything was
fixed.

```php
function oaf_patch(&$node, $data, &$schemas, $depth = 0)
```

Defined in [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$node` |  |  | the schema node to correct, by reference |
| `$data` |  |  | the live value that node should describe |
| `$schemas` |  |  | the shared component schemas, by reference, so a `$ref` target is corrected too |
| `$depth` |  | `0` | how deep the recursion is; it stops past 40 |

## Calls

[oaf_infer()](/code/functions/oaf_infer.md)

## Called from

- [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md) - in [oaf_patch()](/code/functions/oaf_patch.md)


# apiSerializeSupplier()

Shapes a supplier row for the API - legal, trading and display name, registration numbers, type, status, address and counts.

```php
function apiSerializeSupplier(array $r): array
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 173.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the suppliers row as `apiSupplierSelect()` loads it, with type and status names and the counts |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiSuppliersGet()](/code/functions/apiSuppliersGet.md), [apiSuppliersCreate()](/code/functions/apiSuppliersCreate.md), [apiSuppliersUpdate()](/code/functions/apiSuppliersUpdate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`


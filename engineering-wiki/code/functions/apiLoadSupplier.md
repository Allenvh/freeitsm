# apiLoadSupplier()

Loads one supplier row (apiSupplierSelect), or answers 404 and stops.

```php
function apiLoadSupplier(PDO $conn, int $supplierId): array
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 212.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$supplierId` | `int` |  | the suppliers id |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiSupplierSelect()](/code/functions/apiSupplierSelect.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiSuppliersGet()](/code/functions/apiSuppliersGet.md), [apiSuppliersCreate()](/code/functions/apiSuppliersCreate.md), [apiSuppliersUpdate()](/code/functions/apiSuppliersUpdate.md), [apiSupplierContactsList()](/code/functions/apiSupplierContactsList.md), [apiSupplierContactsCreate()](/code/functions/apiSupplierContactsCreate.md), [apiSupplierContactsUpdate()](/code/functions/apiSupplierContactsUpdate.md), [apiSupplierContactsDelete()](/code/functions/apiSupplierContactsDelete.md)


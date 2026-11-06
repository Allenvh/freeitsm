# supplierDetail()

Everything about one supplier this analyst may see, or null.

```php
function supplierDetail(PDO $conn, int $analystId, int $supplierId): ?array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 701.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$supplierId` | `int` |  | the suppliers id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 705 | `supplierDetail` | read [suppliers](/code/tables/suppliers.md), read [supplier_types](/code/tables/supplier_types.md), read [supplier_statuses](/code/tables/supplier_statuses.md) |  |
| 724 | `supplierDetail` | read [contacts](/code/tables/contacts.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [peopleCanSeeSuppliers()](/code/functions/peopleCanSeeSuppliers.md), [peopleContactName()](/code/functions/peopleContactName.md), [peopleContractorTasks()](/code/functions/peopleContractorTasks.md), [peopleSupplierAssets()](/code/functions/peopleSupplierAssets.md), [peopleSupplierContracts()](/code/functions/peopleSupplierContracts.md), [peopleSupplierDomains()](/code/functions/peopleSupplierDomains.md), [peopleSupplierName()](/code/functions/peopleSupplierName.md)

## Called from

- [people/supplier.php](/code/files/people/supplier.php.md)


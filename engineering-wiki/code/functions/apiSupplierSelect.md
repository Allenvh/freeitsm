# apiSupplierSelect()

The SELECT for suppliers, with type and status names and their numbers of contacts and contracts.

```php
function apiSupplierSelect(): string
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 156.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 157 | `apiSupplierSelect` | read [contacts](/code/tables/contacts.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [supplier_types](/code/tables/supplier_types.md), read [supplier_statuses](/code/tables/supplier_statuses.md) |  |

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiLoadSupplier()](/code/functions/apiLoadSupplier.md)


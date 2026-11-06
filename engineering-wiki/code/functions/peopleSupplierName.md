# peopleSupplierName()

The name to show for a supplier: trading name, else legal name, else #id.

```php
function peopleSupplierName(array $r): string
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 637.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a suppliers row - trading_name, legal_name and id are read |

**Returns:** `string`

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleSupplierRows()](/code/functions/peopleSupplierRows.md), [supplierDetail()](/code/functions/supplierDetail.md), [supplierContactDetail()](/code/functions/supplierContactDetail.md)


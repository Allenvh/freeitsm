# peopleSupplierRows()

Every supplier, with how many contacts and contracts each has.

```php
function peopleSupplierRows(PDO $conn, int $analystId, array $f = []): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 658.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$f` | `array` | `[]` | q, status ('active' default \| 'all') |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 667 | `peopleSupplierRows` | read [contacts](/code/tables/contacts.md) |  |
| 673 | `peopleSupplierRows` | read [contacts](/code/tables/contacts.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [supplier_types](/code/tables/supplier_types.md), read [supplier_statuses](/code/tables/supplier_statuses.md) |  |

## Calls

[contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [peopleSupplierName()](/code/functions/peopleSupplierName.md)

## Called from

- [people/suppliers.php](/code/files/people/suppliers.php.md)


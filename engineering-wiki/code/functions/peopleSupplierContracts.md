# peopleSupplierContracts()

Contracts with the supplier - the ordinary, "we buy from them" kind.

```php
function peopleSupplierContracts(PDO $conn, int $analystId, int $supplierId): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 818.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$supplierId` | `int` |  | the suppliers id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 823 | `peopleSupplierContracts` | read [contracts](/code/tables/contracts.md), read [contract_statuses](/code/tables/contract_statuses.md) |  |

## Calls

[contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [entityLink()](/code/functions/entityLink.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [supplierDetail()](/code/functions/supplierDetail.md)


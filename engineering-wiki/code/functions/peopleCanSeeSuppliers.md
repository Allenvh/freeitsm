# peopleCanSeeSuppliers()

Suppliers and contacts are Contracts' records: their pages need Contracts too.

```php
function peopleCanSeeSuppliers(PDO $conn, int $analystId): bool
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 627.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [supplierDetail()](/code/functions/supplierDetail.md), [supplierContactDetail()](/code/functions/supplierContactDetail.md)


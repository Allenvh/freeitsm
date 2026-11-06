# peopleSupplierAssets()

Assets bought from the supplier, in companies the analyst can access.

```php
function peopleSupplierAssets(PDO $conn, int $analystId, int $supplierId): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 847.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$supplierId` | `int` |  | the suppliers id, matched on assets.supplier_id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 850 | `peopleSupplierAssets` | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 857 | `peopleSupplierAssets` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [peopleScope()](/code/functions/peopleScope.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [supplierDetail()](/code/functions/supplierDetail.md)


# leaseTenant()

The lease's company as a real id (NULL read as Default).

```php
function leaseTenant(PDO $conn, array $lease): int
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$lease` | `array` |  | the leases row, as from `leaseLoad()` |

**Returns:** `int`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md), [leaseGet()](/code/functions/leaseGet.md), [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md)


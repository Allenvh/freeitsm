# leaseVisibilitySql()

" AND (...)" - leases in any company this analyst can reach. For links, the asset panel, documents.

```php
function leaseVisibilitySql(PDO $conn, int $analystId, string $l = 'l'): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$l` | `string` | `'l'` | the leases table's alias in the calling query |

**Returns:** `array`

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseCanView()](/code/functions/leaseCanView.md), [leaseLoad()](/code/functions/leaseLoad.md), [leaseHistoryForAsset()](/code/functions/leaseHistoryForAsset.md)


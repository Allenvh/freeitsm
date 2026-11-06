# leaseSyncAllCalendar()

Every asset-date calendar entry (warranty, "Lease ends", lease notice) - it calls `leaseSyncCalendar()` itself.

```php
function leaseSyncAllCalendar(PDO $conn): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 821.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md), [leaseAssetRemove()](/code/functions/leaseAssetRemove.md)


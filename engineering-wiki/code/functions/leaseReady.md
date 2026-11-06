# leaseReady()

True once Database Verification has created the lease tables.

```php
function leaseReady(PDO $conn): bool
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `leaseReady` | read [lease_assets](/code/tables/lease_assets.md) |  |
| 44 | `leaseReady` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Called from

- [api/assets/delete_lease_outcome.php](/code/files/api/assets/delete_lease_outcome.php.md)
- [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md) - in [leaseEndpoint()](/code/functions/leaseEndpoint.md)
- [api/assets/leases/due_back.php](/code/files/api/assets/leases/due_back.php.md)
- [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md)
- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)
- [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) - in [awcSyncKind()](/code/functions/awcSyncKind.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseOutcomes()](/code/functions/leaseOutcomes.md), [leaseActiveRowForAsset()](/code/functions/leaseActiveRowForAsset.md), [leaseHistoryForAsset()](/code/functions/leaseHistoryForAsset.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::moveToCompany`
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)


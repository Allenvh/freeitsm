# leaseActiveRowForAsset()

The asset's ACTIVE lease row with its effective dates, or null.

```php
function leaseActiveRowForAsset(PDO $conn, int $assetId): ?array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 678.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 682 | `leaseActiveRowForAsset` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Calls

[leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseReady()](/code/functions/leaseReady.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSyncAsset()](/code/functions/leaseSyncAsset.md), [leaseAssetDatesEdited()](/code/functions/leaseAssetDatesEdited.md)


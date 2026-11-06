# leaseHistoryForAsset()

For the asset panel: every lease this asset has been on, newest first.

```php
function leaseHistoryForAsset(PDO $conn, int $analystId, int $assetId): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 740.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 747 | `leaseHistoryForAsset` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Calls

[leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseReady()](/code/functions/leaseReady.md), [leaseVisibilitySql()](/code/functions/leaseVisibilitySql.md)

## Called from

- [api/assets/leases/for_asset.php](/code/files/api/assets/leases/for_asset.php.md)


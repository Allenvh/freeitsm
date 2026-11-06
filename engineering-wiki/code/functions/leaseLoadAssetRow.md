# leaseLoadAssetRow()

Load a lease_assets row the actor may touch.

```php
function leaseLoadAssetRow(PDO $conn, int $analystId, int $id): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 598.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the lease_assets row id (not the asset id) |

**Returns:** array{0:array,1:array,2:array} [row, schedule, lease]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 600 | `leaseLoadAssetRow` | read [lease_assets](/code/tables/lease_assets.md) |  |

## Calls

[leaseLoadSchedule()](/code/functions/leaseLoadSchedule.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md), [leaseAssetRemove()](/code/functions/leaseAssetRemove.md)


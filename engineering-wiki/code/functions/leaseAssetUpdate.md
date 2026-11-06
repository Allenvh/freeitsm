# leaseAssetUpdate()

Change one asset on a schedule: outcome_id, outcome_date, start_date / end_date (its own dates - blank = the schedule's), notes. Moving it to a CLOSED outcome stamps today as the outcome date if none is given.

```php
function leaseAssetUpdate(PDO $conn, ActorContext $ctx, int $id, array $in): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 616.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the lease_assets row id (not the asset id) |
| `$in` | `array` |  | any of outcome_id, outcome_date, start_date, end_date, notes; a key left out keeps the current value |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 625 | `leaseAssetUpdate` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 646 | `leaseAssetUpdate` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 649 | `leaseAssetUpdate` | read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 654 | `leaseAssetUpdate` | **update** [lease_assets](/code/tables/lease_assets.md) (`outcome_id`, `start_date`, `end_date`, `outcome_date`, `notes`, `updated_datetime`) |  |

## Calls

[leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseDate()](/code/functions/leaseDate.md), [leaseLoadAssetRow()](/code/functions/leaseLoadAssetRow.md), [leaseSyncAllCalendar()](/code/functions/leaseSyncAllCalendar.md), [leaseSyncAsset()](/code/functions/leaseSyncAsset.md), [leaseText()](/code/functions/leaseText.md)

## Called from

- [api/assets/leases/asset_update.php](/code/files/api/assets/leases/asset_update.php.md)


# leaseAssetRemove()

Take an asset off a schedule altogether (a mistake, not a return - use an outcome for that).

```php
function leaseAssetRemove(PDO $conn, ActorContext $ctx, int $id): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 666.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the lease_assets row id (not the asset id) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 669 | `leaseAssetRemove` | **delete** [lease_assets](/code/tables/lease_assets.md) |  |

## Calls

[leaseLoadAssetRow()](/code/functions/leaseLoadAssetRow.md), [leaseSyncAllCalendar()](/code/functions/leaseSyncAllCalendar.md)

## Called from

- [api/assets/leases/asset_remove.php](/code/files/api/assets/leases/asset_remove.php.md)


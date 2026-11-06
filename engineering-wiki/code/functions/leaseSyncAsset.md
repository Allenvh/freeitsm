# leaseSyncAsset()

Write the effective dates onto the asset (audited, through AssetsService).

```php
function leaseSyncAsset(PDO $conn, ActorContext $ctx, int $assetId): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 692.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[leaseActiveRowForAsset()](/code/functions/leaseActiveRowForAsset.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md), [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md), [leaseSyncSchedule()](/code/functions/leaseSyncSchedule.md)


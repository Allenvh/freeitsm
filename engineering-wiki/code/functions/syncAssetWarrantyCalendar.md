# syncAssetWarrantyCalendar()

Rebuilds the auto-generated warranty-expiry and lease-end calendar entries from the asset dates, then the lease "give notice" entries.

Each kind follows its own setting (asset_warranty_surface, asset_lease_surface)
and is skipped quietly while its column has not been added by Database
Verification yet.

```php
function syncAssetWarrantyCalendar(PDO $conn): array
```

Defined in [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{success:bool, synced?:int, error?:string}

## Calls

[awcColumnExists()](/code/functions/awcColumnExists.md), [awcGetSetting()](/code/functions/awcGetSetting.md), [awcSyncKind()](/code/functions/awcSyncKind.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md)

## Called from

- [api/assets/sync_warranty_calendar.php](/code/files/api/assets/sync_warranty_calendar.php.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSyncAllCalendar()](/code/functions/leaseSyncAllCalendar.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::syncWarranty`


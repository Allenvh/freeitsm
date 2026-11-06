# intuneSyncDisks()

Populate asset_disks with the single device-level total/free figure Intune provides — but only for assets that have NO agent-sourced rows. The PS inventory agent's per-drive data is more accurate, so we never overwrite it.

Returns the number of asset_disks rows inserted (one per eligible asset).

```php
function intuneSyncDisks(PDO $conn): int
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 390.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 395 | `intuneSyncDisks` | read [intune_devices](/code/tables/intune_devices.md), read [asset_disks](/code/tables/asset_disks.md) |  |
| 410 | `intuneSyncDisks` | **delete** [asset_disks](/code/tables/asset_disks.md) |  |
| 412 | `intuneSyncDisks` | **insert** [asset_disks](/code/tables/asset_disks.md) (`asset_id`, `drive`, `label`, `file_system`, `size_bytes`, `free_bytes`, `used_percent`, `source`) |  |

## Calls

[intuneOsToDrive()](/code/functions/intuneOsToDrive.md)

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneRunSync()](/code/functions/intuneRunSync.md)


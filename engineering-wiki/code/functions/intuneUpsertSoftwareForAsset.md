# intuneUpsertSoftwareForAsset()

Wipe and re-write the source='intune' software rows for a single asset. Apps catalogue (software_inventory_apps) is shared with the agent — we dedupe on (display_name, publisher).

```php
function intuneUpsertSoftwareForAsset(PDO $conn, int $assetId, array $apps): int
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 734.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$apps` | `array` |  | Graph detectedApps entries (displayName, publisher, version, sizeInByte); entries with no name are skipped |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 736 | `intuneUpsertSoftwareForAsset` | **delete** [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 743 | `intuneUpsertSoftwareForAsset` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 747 | `intuneUpsertSoftwareForAsset` | **insert** [software_inventory_apps](/code/tables/software_inventory_apps.md) (`display_name`, `publisher`) |  |
| 750 | `intuneUpsertSoftwareForAsset` | **insert** [software_inventory_detail](/code/tables/software_inventory_detail.md) (`host_id`, `app_id`, `display_version`, `estimated_size`, `system_component`, `created_at`, `last_seen`, `source`) |  |

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneRunAppSyncJob()](/code/functions/intuneRunAppSyncJob.md)


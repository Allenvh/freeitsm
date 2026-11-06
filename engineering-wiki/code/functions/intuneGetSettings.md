# intuneGetSettings()

Load the InTune connection settings from system_settings, decrypting the sensitive values. Returns null if any required setting is missing.

```php
function intuneGetSettings(PDO $conn): ?array
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 16.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 18 | `intuneGetSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md)

## Called from

- [api/intune/create_app_sync_job.php](/code/files/api/intune/create_app_sync_job.php.md)
- [api/intune/sync.php](/code/files/api/intune/sync.php.md)
- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneLinkDevicesToAssets()](/code/functions/intuneLinkDevicesToAssets.md), [intuneRunSync()](/code/functions/intuneRunSync.md), [intuneRunAppSyncJob()](/code/functions/intuneRunAppSyncJob.md)


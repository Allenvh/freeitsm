# intuneFetchDeviceApps()

Fetch the detectedApps array for a single Intune-managed device.

```php
function intuneFetchDeviceApps(string $intuneId, array $settings): array
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 720.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$intuneId` | `string` |  | the device's Graph managedDevice id (intune_devices.intune_id) |
| `$settings` | `array` |  | the connection settings from `intuneGetSettings()` |

**Returns:** `array`

## Calls

[intuneGraphGet()](/code/functions/intuneGraphGet.md)

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneRunAppSyncJob()](/code/functions/intuneRunAppSyncJob.md)


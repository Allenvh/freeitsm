# intuneUpsertDevice()

Upsert a single Graph managedDevice into intune_devices. Keyed on intune_id.

```php
function intuneUpsertDevice(PDO $conn, array $d): void
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 180.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$d` | `array` |  | one managedDevice object from Graph, decoded (id, deviceName, serialNumber, ...); stored whole in raw_json |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 183 | `intuneUpsertDevice` | **insert** [intune_devices](/code/tables/intune_devices.md) (`intune_id`, `device_name`, `user_principal_name`, `user_display_name`, `user_id`, `operating_system`, `os_version`, `compliance_state`, `management_state`, `managed_device_owner_type`, `device_enrollment_type`, `device_registration_state`) |  |

## Calls

[intuneIsoToMysql()](/code/functions/intuneIsoToMysql.md)

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneRunSync()](/code/functions/intuneRunSync.md)


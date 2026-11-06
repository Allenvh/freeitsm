# intuneOsToDrive()

Map an Intune operating_system string to a sensible "drive" label for asset_disks. Intune only gives us a single device-level total/free figure, so we fake a single drive entry whose name reflects the platform.

```php
function intuneOsToDrive(?string $os): string
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 372.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$os` | `?string` |  | Intune's operating_system text, e.g. 'Windows' or 'iOS'; null or '' gives 'System' |

**Returns:** `string`

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneSyncDisks()](/code/functions/intuneSyncDisks.md)


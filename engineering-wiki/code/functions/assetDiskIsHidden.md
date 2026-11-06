# assetDiskIsHidden()

Does any of `$rules` hide this drive?

⚠️ Compared as STRINGS after a loose null check, because size_bytes arrives
from PDO as a string on one side and may be an int on the other. `==` would
make NULL and 0 equal, and a 0-byte drive is a real thing here — that is
precisely the Microsoft Virtual Disk this feature was built for.

```php
function assetDiskIsHidden(array $rules, ?string $model, $sizeBytes): bool
```

Defined in [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rules` | `array` |  | the rules from `assetDiskHideRulesFor()` - each {id, asset_id, model, size_bytes} |
| `$model` | `?string` |  | the drive's model as the agent reported it; null when it reported none |
| `$sizeBytes` |  |  | the drive's size in bytes (string from PDO or int); null when unknown |

**Returns:** `bool`

## Called from

- [api/assets/get_asset_disks.php](/code/files/api/assets/get_asset_disks.php.md)


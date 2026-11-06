# storagePersistenceVolumeName()

A volume name derived from the directory, stable and readable.

```php
function storagePersistenceVolumeName(string $rel): string
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 353.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the directory as listed by `storagePersistenceDirectories()`, e.g. 'tickets/attachments' |

**Returns:** `string`

## Called from

- [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md) - in [storagePersistenceSuggestedVolumes()](/code/functions/storagePersistenceSuggestedVolumes.md), [storagePersistenceOverrideFile()](/code/functions/storagePersistenceOverrideFile.md)


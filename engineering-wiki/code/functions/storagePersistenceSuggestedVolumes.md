# storagePersistenceSuggestedVolumes()

The volume lines an operator would need to add, ready to paste.

Only the directories actually at risk are offered. A block that lists
everything, including what is already correct, invites the operator to paste
over a working configuration.

```php
function storagePersistenceSuggestedVolumes(array $report): array
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 336.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$report` | `array` |  | the report from `storagePersistenceReport()` |

**Returns:** `array`

## Calls

[storagePersistenceVolumeName()](/code/functions/storagePersistenceVolumeName.md)

## Called from

- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)


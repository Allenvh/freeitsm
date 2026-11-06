# storagePersistenceReport()

The whole picture, for whichever screen is asking.

'applicable' is false on anything that is not a container, and every caller
must check it before showing the operator anything at all. On a native
install the question is meaningless — `git pull` does not delete these
folders — and a warning there would be pure noise.

```php
function storagePersistenceReport(): array
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 229.

**Returns:** `array`

## Calls

[storagePersistenceDirectories()](/code/functions/storagePersistenceDirectories.md), [storagePersistenceInContainer()](/code/functions/storagePersistenceInContainer.md), [storagePersistenceStatus()](/code/functions/storagePersistenceStatus.md)

## Called from

- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)
- [setup/index.php](/code/files/setup/index.php.md)
- [system/index.php](/code/files/system/index.php.md)


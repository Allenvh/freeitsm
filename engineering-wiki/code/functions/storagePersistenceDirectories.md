# storagePersistenceDirectories()

Every directory the running application writes user files into.

'path'  absolute, resolved from the application root
'rel'   how an operator would recognise it, and what goes in a volume line
'label' plain English — what an operator loses if this one is not persisted

```php
function storagePersistenceDirectories(): array
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 153.

**Returns:** `array`

## Called from

- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)
- [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md) - in [storagePersistenceReport()](/code/functions/storagePersistenceReport.md)


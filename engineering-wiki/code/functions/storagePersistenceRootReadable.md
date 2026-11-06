# storagePersistenceRootReadable()

Would `open_basedir` stop us reading /.dockerenv?

Diagnostics only — nothing decides anything on this. It exists so D013 can say
"could not look" instead of reporting a blocked read as "not a container",
which are very different statements to put in front of an operator.

```php
function storagePersistenceRootReadable(): bool
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 135.

**Returns:** `bool`

## Called from

- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)


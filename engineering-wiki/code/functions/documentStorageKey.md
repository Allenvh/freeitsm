# documentStorageKey()

The key for a newly stored file.

Flat, and that is fine: at the few thousand documents this is built for, a
single directory is not a problem worth solving. Sharding into subdirectories,
or moving to object storage entirely, changes THIS function and nothing else —
which is the entire reason the column holds a key rather than a path.

```php
function documentStorageKey(string $storedName): string
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 754.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$storedName` | `string` |  | the random file name `uploadStoreFile()` gave the upload (its 'stored_name') |

**Returns:** `string`

## Called from

- [api/documents/save.php](/code/files/api/documents/save.php.md)


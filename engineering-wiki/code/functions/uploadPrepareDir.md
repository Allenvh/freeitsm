# uploadPrepareDir()

Create an upload directory and drop execution protection into it.

🔑 Called on every store, not only on create — so a directory that already
exists from before this file was written gets protected the next time
anything is uploaded into it.

```php
function uploadPrepareDir(string $dir): void
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 224.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` |  | absolute path of the upload directory; created (with parents) if missing |

**Returns:** `void`

## Calls

[uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md)

## Called from

- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesStorageRoot()](/code/functions/filesStorageRoot.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadStoreFile()](/code/functions/uploadStoreFile.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)


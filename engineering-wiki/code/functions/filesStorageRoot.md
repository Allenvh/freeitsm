# filesStorageRoot()

The storage root, created and guarded on first use. No trailing slash.

```php
function filesStorageRoot(PDO $conn): string
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[filesDefaultStorageRoot()](/code/functions/filesDefaultStorageRoot.md), [filesSetting()](/code/functions/filesSetting.md), [uploadPrepareDir()](/code/functions/uploadPrepareDir.md)

## Called from

- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesNewStoragePath()](/code/functions/filesNewStoragePath.md), [filesAbsolutePath()](/code/functions/filesAbsolutePath.md), [filesIncomingPath()](/code/functions/filesIncomingPath.md)


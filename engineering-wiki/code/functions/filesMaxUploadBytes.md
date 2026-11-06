# filesMaxUploadBytes()

The largest file an analyst may upload, in bytes, from the files_max_upload_mb setting.

```php
function filesMaxUploadBytes(PDO $conn): int
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Calls

[filesSetting()](/code/functions/filesSetting.md)

## Called from

- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [files/index.php](/code/files/files/index.php.md)


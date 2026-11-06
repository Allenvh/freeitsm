# uploadFormatBytes()

A byte count as readable text - "1.5 MB", "300 KB" or "512 bytes".

```php
function uploadFormatBytes(int $bytes): string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 663.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bytes` | `int` |  | a size in bytes |

**Returns:** `string`

## Called from

- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadStoreFile()](/code/functions/uploadStoreFile.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)


# uploadDetectMime()

The file's real mime type, or null if this server cannot tell us.

```php
function uploadDetectMime(string $path): ?string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 625.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | absolute path of the file on disk |

**Returns:** `?string`

## Called from

- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesDetectMime()](/code/functions/filesDetectMime.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadStoreFile()](/code/functions/uploadStoreFile.md)


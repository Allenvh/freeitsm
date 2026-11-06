# uploadCleanName()

The original filename, reduced to something safe to STORE AND DISPLAY.

Not used to build the path — see uploadStoreFile — but it is echoed into a
Content-Disposition header and rendered in lists, so it must not carry a
newline (header injection), a quote (breaks out of the header's filename),
or any path segment.

```php
function uploadCleanName(string $name): string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 607.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the filename as the sender gave it - untrusted, may hold a path |

**Returns:** `string`

## Called from

- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesCleanName()](/code/functions/filesCleanName.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadStoreFile()](/code/functions/uploadStoreFile.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)


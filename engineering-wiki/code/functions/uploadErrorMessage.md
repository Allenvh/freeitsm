# uploadErrorMessage()

PHP's upload error codes as something a person can act on.

```php
function uploadErrorMessage(int $code): string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 644.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `int` |  | a PHP upload error code, the UPLOAD_ERR_* in `$_FILES`[...]['error'] |

**Returns:** `string`

## Called from

- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadStoreFile()](/code/functions/uploadStoreFile.md)


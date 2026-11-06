# attachmentMaxBytes()

The install-wide largest single attachment, in bytes. What uploads.php uses when a caller sets none.

```php
function attachmentMaxBytes(?PDO $conn = null): int
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Calls

[attachmentMb()](/code/functions/attachmentMb.md), [attachmentSetting()](/code/functions/attachmentSetting.md)

## Called from

- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [oversizeRules()](/code/functions/oversizeRules.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadStoreFile()](/code/functions/uploadStoreFile.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)


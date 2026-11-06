# filesFreeName()

"Report.pdf" -> "Report (2).pdf", the first free one.

```php
function filesFreeName(PDO $conn, ?int $parentId, string $name): string
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 236.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$parentId` | `?int` |  | the folder the name must be free in; null is the top level |
| `$name` | `string` |  | the name wanted; returned unchanged when nothing visible already uses it |

**Returns:** `string`

## Calls

[filesNameTaken()](/code/functions/filesNameTaken.md)

## Called from

- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/files/transfer.php](/code/files/api/files/transfer.php.md) - in [filesTransferItem()](/code/functions/filesTransferItem.md), [filesTransferFolder()](/code/functions/filesTransferFolder.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)


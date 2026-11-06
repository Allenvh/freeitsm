# filesItem()

A live file row, or null.

```php
function filesItem(PDO $conn, int $itemId): ?array
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 106.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the files_items id; null comes back if it or any folder above it is deleted |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 108 | `filesItem` | read [files_items](/code/tables/files_items.md) |  |

## Called from

- [api/files/download.php](/code/files/api/files/download.php.md)
- [api/files/recent.php](/code/files/api/files/recent.php.md)
- [api/files/transfer.php](/code/files/api/files/transfer.php.md) - in [filesTransferItem()](/code/functions/filesTransferItem.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [api/files/view.php](/code/files/api/files/view.php.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesNeedItem()](/code/functions/filesNeedItem.md), [filesShortcutTarget()](/code/functions/filesShortcutTarget.md)


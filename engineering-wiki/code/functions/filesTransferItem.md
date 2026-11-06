# filesTransferItem()

RuntimeException = a refusal to report for this one entry.

```php
function filesTransferItem(PDO $conn, int $analystId, string $mode, int $id, int $target): void
```

Defined in [api/files/transfer.php](/code/files/api/files/transfer.php.md), line 63.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$mode` | `string` |  | 'move' or 'copy' |
| `$id` | `int` |  | the files_items id being moved or copied |
| `$target` | `int` |  | the destination folder id; 0 or less is refused, as files cannot sit at the top level |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `filesTransferItem` | **update** [files_items](/code/tables/files_items.md) (`folder_id`, `name`, `updated_by`, `updated_datetime`) |  |

## Calls

[filesAudit()](/code/functions/filesAudit.md), [filesCopyItemRow()](/code/functions/filesCopyItemRow.md), [filesFreeName()](/code/functions/filesFreeName.md), [filesItem()](/code/functions/filesItem.md)

## Called from

- [api/files/transfer.php](/code/files/api/files/transfer.php.md)


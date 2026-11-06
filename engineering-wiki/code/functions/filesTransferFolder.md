# filesTransferFolder()

Moves or copies one folder to the target folder (0 = the top level). RuntimeException = a refusal to report for this one entry.

Move needs Modify and copy needs Download on the folder, and a folder can never go
inside itself. A move keeps the folder's own permission entries; a copy is built by
`filesCopyFolderTree()`. Anything that lands at the top level gives the person doing it
Full control. Both are audited.

```php
function filesTransferFolder(PDO $conn, int $analystId, string $mode, int $id, int $target): void
```

Defined in [api/files/transfer.php](/code/files/api/files/transfer.php.md), line 126.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$mode` | `string` |  | 'move' or 'copy' |
| `$id` | `int` |  | the files_folders id being moved or copied |
| `$target` | `int` |  | the destination folder id; 0 means the top level |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 147 | `filesTransferFolder` | **update** [files_folders](/code/tables/files_folders.md) (`parent_id`, `name`, `updated_by`, `updated_datetime`) |  |

## Calls

[filesAudit()](/code/functions/filesAudit.md), [filesCopyFolderTree()](/code/functions/filesCopyFolderTree.md), [filesFreeName()](/code/functions/filesFreeName.md), [filesGrantCreatorFull()](/code/functions/filesGrantCreatorFull.md)

## Called from

- [api/files/transfer.php](/code/files/api/files/transfer.php.md)


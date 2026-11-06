# filesCopyFolderTree()

Copies a folder and, recursively, every file and subfolder under it that the copier can at least Download, and returns the new folder's id.

The new folders inherit their permissions and carry no entries of their own. A
subfolder that shuts the copier out is skipped, so copying never becomes a way to read
what they cannot read.

```php
function filesCopyFolderTree(
    PDO $conn,
    int $analystId,
    int $srcId,
    ?int $parentId,
    string $name
): int
```

Defined in [api/files/transfer.php](/code/files/api/files/transfer.php.md), line 184.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$srcId` | `int` |  | the folder being copied |
| `$parentId` | `?int` |  | the folder the copy goes into; null for the top level |
| `$name` | `string` |  | the new folder's name, already made unique where it lands |

**Returns:** `int`

**Writes:** files_folders, files_items, files_versions - the copied tree; file copies share the source's stored bytes

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 186 | `filesCopyFolderTree` | **insert** [files_folders](/code/tables/files_folders.md) (`parent_id`, `name`, `inherit_permissions`, `created_by`, `created_datetime`) |  |
| 194 | `filesCopyFolderTree` | read [files_items](/code/tables/files_items.md) |  |

> [!CAUTION]
> The comment names `files_versions` but no SQL in this function touches it - the comment may be out of date (or the SQL is built elsewhere).

## Calls

[filesCopyItemRow()](/code/functions/filesCopyItemRow.md)

## Called from

- [api/files/transfer.php](/code/files/api/files/transfer.php.md) - in [filesTransferFolder()](/code/functions/filesTransferFolder.md), [filesCopyFolderTree()](/code/functions/filesCopyFolderTree.md)


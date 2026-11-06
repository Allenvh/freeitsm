# filesGrantCreatorFull()

Gives an analyst Full control on a folder, upgrading an existing entry - used when a folder lands at the top level, as when creating one there.

```php
function filesGrantCreatorFull(PDO $conn, int $analystId, int $folderId): void
```

Defined in [api/files/transfer.php](/code/files/api/files/transfer.php.md), line 215.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `int` |  | the folder to give `$analystId` Full control on |

**Returns:** `void`

**Writes:** files_permissions(level) - an analyst entry for the folder

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 217 | `filesGrantCreatorFull` | **insert** [files_permissions](/code/tables/files_permissions.md) (`folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime`) |  |

## Called from

- [api/files/transfer.php](/code/files/api/files/transfer.php.md) - in [filesTransferFolder()](/code/functions/filesTransferFolder.md)


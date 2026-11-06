# handleRename()

Renames a knowledge folder the caller can read, and audits the change.

```php
function handleRename(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 208.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: id and the new name |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 215 | `handleRename` | **update** [knowledge_folders](/code/tables/knowledge_folders.md) (`name`, `modified_datetime`) |  |

## Calls

[knowledgeAudit()](/code/functions/knowledgeAudit.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)


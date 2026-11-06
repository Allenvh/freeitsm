# knowledgeAudit()

Thin alias onto includes/knowledge/audit.php, the one writer.

```php
function knowledgeAudit(
    PDO $conn,
    string $type,
    int $id,
    string $action,
    int $analystId,
    ?array $detail
): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 477.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | what was acted on: 'folder' or 'article' |
| `$id` | `int` |  | that folder's or article's id |
| `$action` | `string` |  | one of KNOWLEDGE_AUDIT_ACTIONS, e.g. 'create', 'move', 'delete' |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$detail` | `?array` |  | extra facts stored as JSON with the row, or null |

**Returns:** `void`

## Calls

[knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [handleCreate()](/code/functions/handleCreate.md), [handleRename()](/code/functions/handleRename.md), [handleMove()](/code/functions/handleMove.md), [handleDelete()](/code/functions/handleDelete.md), [handleMoveArticle()](/code/functions/handleMoveArticle.md), [handleAddShortcut()](/code/functions/handleAddShortcut.md), [handleRemoveShortcut()](/code/functions/handleRemoveShortcut.md)


# knowledgeFolderIndex()

The folder tree as [id => ['parent'=>?int,'restricted'=>int,'inherit'=>int]].

```php
function knowledgeFolderIndex(PDO $conn): array
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 875.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 881 | `knowledgeFolderIndex` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [knowledgeFolderIsReadable()](/code/functions/knowledgeFolderIsReadable.md), [handleMove()](/code/functions/handleMove.md)
- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [resolveInherited()](/code/functions/resolveInherited.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAclSql()](/code/functions/knowledgeAclSql.md)


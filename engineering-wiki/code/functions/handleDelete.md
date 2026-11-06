# handleDelete()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

Delete a folder.

⚠️ REFUSES WHILE IT HOLDS ANYTHING. Deleting a folder full of documents would
either destroy them or orphan them, and neither is something to do on one
click. Empty it first — which is a decision about the documents, made
deliberately, rather than a side effect of tidying the tree.

```php
function handleDelete(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 271.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: id of the folder to delete |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 277 | `handleDelete` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 283 | `handleDelete` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 292 | `handleDelete` | **delete** [knowledge_acl](/code/tables/knowledge_acl.md) |  |
| 293 | `handleDelete` | **delete** [knowledge_folders](/code/tables/knowledge_folders.md) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [knowledgeAudit()](/code/functions/knowledgeAudit.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)


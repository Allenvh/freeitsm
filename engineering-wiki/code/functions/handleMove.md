# handleMove()

Re-parent a folder.

⚠️ REFUSES A CYCLE. Dropping a folder into its own descendant would detach the
whole branch from the root: every folder in it still exists, still holds
documents, and is reachable from nothing. The permission walk survives a cycle
(it fails closed) but the tree does not, so this is refused at the door rather
than tolerated later.

```php
function handleMove(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 232.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: id, and parent_id (empty or null for the top level) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 254 | `handleMove` | **update** [knowledge_folders](/code/tables/knowledge_folders.md) (`parent_id`, `modified_datetime`) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [knowledgeAudit()](/code/functions/knowledgeAudit.md), [knowledgeFolderIndex()](/code/functions/knowledgeFolderIndex.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)


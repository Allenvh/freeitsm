# handleList()

> [!NOTE]
> Defined in 3 places (each behind a function_exists() check, or in files never loaded together): [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

The folders this analyst may see, each with the number of articles they may see inside it.

⚠️ THE COUNT USES THE SAME CLAUSE AS THE ARTICLE LIST. A folder that says "4"
and opens to show 2 is the tag-badge bug (#1212) about to happen again, and
for the same reason: two hand-written copies of one rule that were meant to
match. Both ask `knowledgeVisibilitySql()`.

```php
function handleList(PDO $conn, int $analystId): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 80 | `handleList` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 87 | `handleList` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 94 | `handleList` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[knowledgeFolderIsReadable()](/code/functions/knowledgeFolderIsReadable.md), [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)
- [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)


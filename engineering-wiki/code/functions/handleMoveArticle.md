# handleMoveArticle()

File an article into a folder — what a drag-and-drop actually does.

⚠️ BOTH ENDS ARE CHECKED, and they are different questions. The DESTINATION
must be a folder this analyst can reach, or a drop would file a document
somewhere they cannot see it again. The ARTICLE must be one they can read,
because moving something is a way of finding out it exists — an id is a guess
away, and "it moved" and "no such article" must look the same from outside.

Deliberately its own action rather than a save: the editor's save carries a
title and a body, and requiring those to move a row would mean the drag had to
fetch and re-post the whole article — which is also how a drag ends up
overwriting an edit somebody else was making.

```php
function handleMoveArticle(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 316.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: article_id, and folder_id (empty or null for the root) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 331 | `handleMoveArticle` | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`folder_id`, `modified_datetime`) |  |

## Calls

[knowledgeAudit()](/code/functions/knowledgeAudit.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)


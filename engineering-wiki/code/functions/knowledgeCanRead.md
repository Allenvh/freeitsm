# knowledgeCanRead()

May `$viewer` read article `$articleId`?

The boundary check. Built on `knowledgeVisibilitySql()` so it can never
disagree with the list that produced the id — see the header note.

A missing row returns FALSE, matching `analystCanAccessArticle()`'s reasoning:
"let the caller's own 404 handle it" is only safe if every caller HAS a 404,
and that is not a property this function can check. Denying is correct and is
indistinguishable to a legitimate reader, who gets not-found either way.

```php
function knowledgeCanRead(PDO $conn, KnowledgeViewer $viewer, $articleId, array $opts = []): bool
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 435.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` | `KnowledgeViewer` |  | who is reading |
| `$articleId` |  |  | the knowledge article id (cast to int; 0 or less gives false) |
| `$opts` | `array` | `[]` | passed to `knowledgeVisibilitySql()` - see there for lifecycle |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 442 | `knowledgeCanRead` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[knowledgeAuditAdminOverride()](/code/functions/knowledgeAuditAdminOverride.md), [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [api/knowledge/audit.php](/code/files/api/knowledge/audit.php.md)
- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [handleMoveArticle()](/code/functions/handleMoveArticle.md), [handleAddShortcut()](/code/functions/handleAddShortcut.md)
- [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiLoadArticle()](/code/functions/apiLoadArticle.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)


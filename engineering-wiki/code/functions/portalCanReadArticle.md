# portalCanReadArticle()

May this portal requester read this article in the Help Centre?

The single-article form of `portalKnowledgeScope()`, for a caller that already
has an id - the Help Centre's document download (GH #177). Built on the scope
rather than beside it, so it cannot disagree with the article page that
listed the document.

```php
function portalCanReadArticle(PDO $conn, int $userId, int $articleId): bool
```

Defined in [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), line 101.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user's users.id, from the session's ss_user_id |
| `$articleId` | `int` |  | knowledge_articles.id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `portalCanReadArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[portalKnowledgeScope()](/code/functions/portalKnowledgeScope.md), [portalUserTenantId()](/code/functions/portalUserTenantId.md)

## Called from

- [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md) - in [helpCentreDocumentForPortalUser()](/code/functions/helpCentreDocumentForPortalUser.md)


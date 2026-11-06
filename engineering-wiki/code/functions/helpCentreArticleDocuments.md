# helpCentreArticleDocuments()

The documents on an article that the Help Centre shows, newest first.

The caller MUST already have established that the requester may read the
article (get_knowledge_article.php does, in the same query that fetched it).
Returns [] before Database Verification has added the column.

```php
function helpCentreArticleDocuments(PDO $conn, int $articleId): array
```

Defined in [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` | `int` |  | knowledge_articles.id |

**Returns:** array<int,array<string,mixed>> id, kind, title, description, original_name, size_bytes, external_url

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 | `helpCentreArticleDocuments` | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |

## Calls

[documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md)

## Called from

- [api/self-service/get_knowledge_article.php](/code/files/api/self-service/get_knowledge_article.php.md)


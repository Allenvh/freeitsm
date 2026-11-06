# helpCentreArticleIsCustomerFacing()

Is this article one that customers could read in the Help Centre at all - published, not archived, and with an audience that includes signed-in customers? Drives the explanation on the analyst's Documents panel only; it is NOT an access check (that is `portalCanReadArticle()`, which also knows the requester's company and the access list).

```php
function helpCentreArticleIsCustomerFacing(PDO $conn, int $articleId): bool
```

Defined in [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` | `int` |  | knowledge_articles.id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 | `helpCentreArticleIsCustomerFacing` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Called from

- [api/documents/list.php](/code/files/api/documents/list.php.md)


# apiKnowledgeSelect()

The SELECT for knowledge articles, with author, owner, archiver and company names.

```php
function apiKnowledgeSelect(): string
```

Defined in [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), line 50.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `apiKnowledgeSelect` | read [knowledge_articles](/code/tables/knowledge_articles.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## Called from

- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiLoadArticle()](/code/functions/apiLoadArticle.md), [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md)


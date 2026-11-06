# apiKnowledgeArticlesList()

GET /knowledge/articles

```php
function apiKnowledgeArticlesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 192 | `apiKnowledgeArticlesList` | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |
| 272 | `apiKnowledgeArticlesList` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKnowledgePurgeExpired()](/code/functions/apiKnowledgePurgeExpired.md), [apiKnowledgeSelect()](/code/functions/apiKnowledgeSelect.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeArticle()](/code/functions/apiSerializeArticle.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

*Nothing calls this function by name.*


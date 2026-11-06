# apiLoadArticle()

Load an article by id, 404ing if it's gone.

Pass `$apiKey` on a READ path and it also refuses an article belonging to a
company the key can't reach — reported as 404, not 403, so an id can't be used
to probe which articles exist elsewhere. The post-write callers omit it: the
service layer has already run the same check by then.

```php
function apiLoadArticle(PDO $conn, int $articleId, ?array $apiKey = null): array
```

Defined in [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` | `int` |  | the knowledge_articles id |
| `$apiKey` | `?array` | `null` | the calling API key on a read path; null skips the read check (post-write callers, already checked) |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiKnowledgeSelect()](/code/functions/apiKnowledgeSelect.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md)

## Called from

- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesGet()](/code/functions/apiKnowledgeArticlesGet.md), [apiKnowledgeArticlesCreate()](/code/functions/apiKnowledgeArticlesCreate.md), [apiKnowledgeArticlesUpdate()](/code/functions/apiKnowledgeArticlesUpdate.md), [apiKnowledgeArticlesRestore()](/code/functions/apiKnowledgeArticlesRestore.md), [apiKnowledgeVersionsList()](/code/functions/apiKnowledgeVersionsList.md), [apiKnowledgeVersionsGet()](/code/functions/apiKnowledgeVersionsGet.md)


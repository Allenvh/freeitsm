# apiSerializeArticle()

Summary shape (lists) — preview instead of the full body. Detail adds body_html.

```php
function apiSerializeArticle(PDO $conn, array $r, bool $detail = false): array
```

Defined in [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the knowledge_articles row as `apiKnowledgeSelect()` loads it, with people and company names |
| `$detail` | `bool` | `false` | true for the single-article shape (full body_html and has_embedding); false gives a 300-character preview |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiKnowledgeTags()](/code/functions/apiKnowledgeTags.md)

## Called from

- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md), [apiKnowledgeArticlesGet()](/code/functions/apiKnowledgeArticlesGet.md), [apiKnowledgeArticlesCreate()](/code/functions/apiKnowledgeArticlesCreate.md), [apiKnowledgeArticlesUpdate()](/code/functions/apiKnowledgeArticlesUpdate.md), [apiKnowledgeArticlesRestore()](/code/functions/apiKnowledgeArticlesRestore.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`


# apiKnowledgeTags()

The names of an article's tags, alphabetically.

```php
function apiKnowledgeTags(PDO $conn, int $articleId): array
```

Defined in [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` | `int` |  | the knowledge_articles id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `apiKnowledgeTags` | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |

## Called from

- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiSerializeArticle()](/code/functions/apiSerializeArticle.md)


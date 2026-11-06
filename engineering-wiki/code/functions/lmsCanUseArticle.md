# lmsCanUseArticle()

True when the LMS may read `$articleId`'s body. Fails CLOSED: no row, wrong company, or an unexpected error => false.

```php
function lmsCanUseArticle(PDO $conn, int $articleId): bool
```

Defined in [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` | `int` |  | the knowledge article id; only a published, unarchived, shared one passes |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 | `lmsCanUseArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[lmsSharedOnlySql()](/code/functions/lmsSharedOnlySql.md)

## Called from

- [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md)


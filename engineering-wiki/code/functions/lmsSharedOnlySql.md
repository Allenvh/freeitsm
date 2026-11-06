# lmsSharedOnlySql()

SQL fragment restricting a knowledge_articles query to shared articles.

Returns '' on an install that has not run Database Verify yet: the column does
not exist, so every article is shared by definition and referencing it would
throw — which for the picker means a silently EMPTY article list rather than
an error anyone would notice.

```php
function lmsSharedOnlySql(PDO $conn): string
```

Defined in [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

## Called from

- [api/lms/knowledge_articles.php](/code/files/api/lms/knowledge_articles.php.md)
- [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md) - in [lmsCanUseArticle()](/code/functions/lmsCanUseArticle.md)


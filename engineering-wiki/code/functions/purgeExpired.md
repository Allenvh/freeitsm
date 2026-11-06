# purgeExpired()

Permanently deletes articles that have been in the recycle bin longer than the retention period, with their versions and tags, then removes tags no article uses any more. A retention of 0 keeps them forever.

```php
function purgeExpired($conn)
```

Defined in [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), line 121.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Writes:** knowledge_article_versions, knowledge_article_tags, knowledge_articles, knowledge_tags - children first, since the versions foreign key has no cascade

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 128 | `purgeExpired` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 137 | `purgeExpired` | **delete** [knowledge_article_versions](/code/tables/knowledge_article_versions.md) |  |
| 138 | `purgeExpired` | **delete** [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 139 | `purgeExpired` | **delete** [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 142 | `purgeExpired` | **delete** [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |

## Calls

[getRetentionDays()](/code/functions/getRetentionDays.md)

## Called from

- [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md) - in [handleList()](/code/functions/handleList.md)


# filesArticleTarget()

A Knowledge article as a shortcut target, or null when this person may not read it (no Knowledge module, another company's, restricted, archived, a draft, deleted). Knowledge's own visibility rules decide - the same clause its list uses, WITHOUT the administrator floor: that floor is an audited override for opening one article in Knowledge, never a reason for it to show up in a folder.

```php
function filesArticleTarget(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 208.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the knowledge_articles id; 0 or less gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 223 | `filesArticleTarget` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesShortcutTarget()](/code/functions/filesShortcutTarget.md)


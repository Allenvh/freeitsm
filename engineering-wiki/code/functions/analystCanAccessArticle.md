# analystCanAccessArticle()

May this analyst act on this article? Mirrors `analystCanAccessAsset()`, except a SHARED article (tenant_id IS NULL) belongs to everybody, so everybody may reach it — that is what "shared" means.

Permissive when multi-tenancy is off or the column does not exist yet, so a
single-company or un-migrated install behaves exactly as before.

```php
function analystCanAccessArticle(PDO $conn, int $analystId, $articleId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1008.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` |  |  | the Knowledge article (knowledge_articles.id) |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1012 | `analystCanAccessArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyColumnExists()](/code/functions/tenancyColumnExists.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

*Nothing calls this function by name.*


# portalUserTenantId()

The company a portal user belongs to, or null for "shared articles only".

The portal session carries only ss_user_id, so this is a lookup rather than
something the caller can pass in — which also means a caller cannot pass in
somebody else's company.

```php
function portalUserTenantId(PDO $conn, int $userId): ?int
```

Defined in [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user's users.id (from the session's ss_user_id) |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `portalUserTenantId` | read [users](/code/tables/users.md) |  |

## Called from

- [api/self-service/get_knowledge_article.php](/code/files/api/self-service/get_knowledge_article.php.md)
- [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md)
- [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md) - in [portalCanReadArticle()](/code/functions/portalCanReadArticle.md)


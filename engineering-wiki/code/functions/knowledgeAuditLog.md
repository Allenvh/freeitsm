# knowledgeAuditLog()

Record one thing that happened.

```php
function knowledgeAuditLog(
    PDO $conn,
    string $type,
    int $id,
    string $action,
    ?int $analyst = null,
    ?array $detail = null,
    ?int $user = null
): void
```

Defined in [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | 'article' \| 'folder' |
| `$id` | `int` |  | the object it happened to |
| `$action` | `string` |  | one of KNOWLEDGE_AUDIT_ACTIONS |
| `$analyst` | `?int` | `null` | the analyst responsible, or null |
| `$detail` | `?array` | `null` | anything worth keeping; stored as JSON |
| `$user` | `?int` | `null` | a portal user, when the actor is not an analyst |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `knowledgeAuditLog` | **insert** [knowledge_audit](/code/tables/knowledge_audit.md) (`object_type`, `object_id`, `action`, `analyst_id`, `user_id`, `detail`, `ip_address`) |  |

## Called from

- [api/documents/help_centre.php](/code/files/api/documents/help_centre.php.md)
- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [knowledgeAudit()](/code/functions/knowledgeAudit.md)
- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md) - in [handleSet()](/code/functions/handleSet.md)
- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [permAudit()](/code/functions/permAudit.md)
- [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md) - in [knowledgeAuditView()](/code/functions/knowledgeAuditView.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAuditAdminOverride()](/code/functions/knowledgeAuditAdminOverride.md)
- [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) - in `KnowledgeService::saveArticle`, `KnowledgeService::archiveArticle`, `KnowledgeService::restoreArticle`, `KnowledgeService::purgeArticle`


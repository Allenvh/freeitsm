# handleRemove()

Removes one entry from a folder's or article's access list and audits it. The reply's now_unreachable is true when a restricted object is left with nobody on its list.

```php
function handleRemove(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 313.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: object_type, object_id and entry_id (the knowledge_acl row) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 319 | `handleRemove` | **delete** [knowledge_acl](/code/tables/knowledge_acl.md) |  |
| 329 | `handleRemove` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [permAudit()](/code/functions/permAudit.md), [requireObject()](/code/functions/requireObject.md)

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md)


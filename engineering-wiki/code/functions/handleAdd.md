# handleAdd()

Adds an analyst, team, portal user or user group to a folder's or article's access list, and audits it. Adding someone already listed is treated as success.

```php
function handleAdd(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 283.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: object_type, object_id, principal_type and principal_id |

**Returns:** `void`

**Writes:** knowledge_acl - one entry for the object

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 294 | `handleAdd` | **insert** [knowledge_acl](/code/tables/knowledge_acl.md) (`object_type`, `object_id`, `principal_type`, `principal_id`, `created_by_id`, `created_datetime`) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [permAudit()](/code/functions/permAudit.md), [requireObject()](/code/functions/requireObject.md)

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md)


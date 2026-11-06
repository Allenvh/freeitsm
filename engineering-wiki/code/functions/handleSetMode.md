# handleSetMode()

Set Open/Restricted and the inherit tickbox.

⚠️ A POLARITY CHANGE DELETES THE LIST. See the header. The count of what will
be dropped is returned so the interface can say so BEFORE asking again, rather
than reporting a loss after the fact.

```php
function handleSetMode(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 248.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: object_type, object_id, and optionally is_restricted and inherits |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 258 | `handleSetMode` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |
| 261 | `handleSetMode` | **delete** [knowledge_acl](/code/tables/knowledge_acl.md) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [permAudit()](/code/functions/permAudit.md), [requireObject()](/code/functions/requireObject.md)

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md)


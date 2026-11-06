# handleCreate()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

Creates a knowledge folder under the given parent (or at the top level) and audits it.

A new folder always inherits and restricts nothing, so creating one never changes who
can see what is already there. The caller must be able to read the parent.

```php
function handleCreate(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 180.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: name, and parent_id (empty or null for the top level) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 193 | `handleCreate` | **insert** [knowledge_folders](/code/tables/knowledge_folders.md) (`parent_id`, `name`, `is_restricted`, `inherit_permissions`, `created_by_id`, `owner_id`, `created_datetime`, `modified_datetime`) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [knowledgeAudit()](/code/functions/knowledgeAudit.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)


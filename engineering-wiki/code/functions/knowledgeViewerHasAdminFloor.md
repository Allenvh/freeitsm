# knowledgeViewerHasAdminFloor()

Does this viewer hold the administrator floor?

Cap::KNOWLEDGE_MANAGE rather than a new knowledge.admin: it already exists as
the module's umbrella capability and its holders already administer Knowledge,
so inventing one would mean an RBAC seed and a settings row for no extra
safety. Split it out later if it ever earns its own.

Only an ANALYST can hold it. A portal user or a web-chat visitor never does,
whatever else is true of them.

```php
function knowledgeViewerHasAdminFloor(PDO $conn, KnowledgeViewer $viewer): bool
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 485.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` | `KnowledgeViewer` |  | who is reading; only an analyst viewer can hold the floor |

**Returns:** `bool`

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md)

## Called from

- [api/knowledge/audit.php](/code/files/api/knowledge/audit.php.md)
- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [handleList()](/code/functions/handleList.md), [knowledgeFolderIsReadable()](/code/functions/knowledgeFolderIsReadable.md), [handleExceptions()](/code/functions/handleExceptions.md)
- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [knowledgeAclSql()](/code/functions/knowledgeAclSql.md)


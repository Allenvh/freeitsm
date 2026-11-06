# knowledgeAuditHistory()

The history of one object, newest first, with the names filled in.

Names are resolved HERE rather than joined, because an analyst who has since
been deleted must still show as something — a history whose actor column is
blank for everyone who ever left is not a history anybody can use.

```php
function knowledgeAuditHistory(PDO $conn, string $type, int $id, int $limit = 200): array
```

Defined in [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | 'article' or 'folder' |
| `$id` | `int` |  | the article or folder id |
| `$limit` | `int` | `200` | the most entries returned, clamped to 1-500 |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 122 | `knowledgeAuditHistory` | read [knowledge_audit](/code/tables/knowledge_audit.md) |  |
| 137 | `knowledgeAuditHistory` | read [analysts](/code/tables/analysts.md) |  |
| 140 | `knowledgeAuditHistory` | read [users](/code/tables/users.md) |  |

## Called from

- [api/knowledge/audit.php](/code/files/api/knowledge/audit.php.md)


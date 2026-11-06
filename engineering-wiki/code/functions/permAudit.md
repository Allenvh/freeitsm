# permAudit()

Permission changes are the rows people actually come looking for. Never silent.

```php
function permAudit(PDO $conn, string $type, int $id, int $analystId, array $detail): void
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 384.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | 'folder' or 'article' |
| `$id` | `int` |  | that folder's or article's id |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$detail` | `array` |  | what changed, stored as JSON on the audit row |

**Returns:** `void`

## Calls

[knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md)

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [handleSetMode()](/code/functions/handleSetMode.md), [handleAdd()](/code/functions/handleAdd.md), [handleRemove()](/code/functions/handleRemove.md)


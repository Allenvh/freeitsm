# handleSet()

Saves the install-wide folder permission model ('filing', anything else meaning 'containers') and writes an audit row naming the old and new model.

```php
function handleSet(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md), line 171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request; model is 'filing', anything else meaning 'containers' |

**Returns:** `void`

**Writes:** system_settings(knowledge_folder_permission_model)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 176 | `handleSet` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## Calls

[knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md), [knowledgeFolderPermissionModel()](/code/functions/knowledgeFolderPermissionModel.md)

## Called from

- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md)


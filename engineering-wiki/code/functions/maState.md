# maState()

Everything the page shows for one manager.

```php
function maState(PDO $conn, array $m): array
```

Defined in [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$m` | `array` |  | the manager as loaded by `maManager()` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 108 | `maState` | read [manager_grants](/code/tables/manager_grants.md), read [users](/code/tables/users.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md), read [analysts](/code/tables/analysts.md) |  |
| 149 | `maState` | read [users](/code/tables/users.md) |  |

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md), [managerGrantMembers()](/code/functions/managerGrantMembers.md), [managerLinesEditable()](/code/functions/managerLinesEditable.md), [managerReachSql()](/code/functions/managerReachSql.md), [managersSettings()](/code/functions/managersSettings.md), [managerTeamPreviewIds()](/code/functions/managerTeamPreviewIds.md), [maReady()](/code/functions/maReady.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

## Called from

- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md)


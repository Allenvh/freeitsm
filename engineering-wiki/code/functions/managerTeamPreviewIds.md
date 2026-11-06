# managerTeamPreviewIds()

The team as it WOULD be with managers switched on - for the admin screens, so an admin can check who sees what before turning the portal over. Only the master switch is pretended; a missing table still gives nobody. The memo is put back afterwards, so nothing else in the request sees the pretence.

```php
function managerTeamPreviewIds(PDO $conn, int $managerUserId): array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 287.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$managerUserId` | `int` |  | the manager's users.id (a portal user) |

**Returns:** int[]

## Calls

[managersSettings()](/code/functions/managersSettings.md), [managerTeamUserIds()](/code/functions/managerTeamUserIds.md)

## Called from

- [api/system/managers.php](/code/files/api/system/managers.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)


# managerTeamUserIds()

The people a manager may see the tickets of, as user ids. Memoised per request. Empty when managers are off, the manager has left, or - on a multi-company install - the manager belongs to no company.

("No company" is read as the Default company, not as nowhere - see the header.)

```php
function managerTeamUserIds(PDO $conn, int $managerUserId): array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 132.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$managerUserId` | `int` |  | the manager's users.id (a portal user) |

**Returns:** int[]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 143 | `managerTeamUserIds` | read [manager_grants](/code/tables/manager_grants.md) |  |

## Calls

[managerGrantMembers()](/code/functions/managerGrantMembers.md), [managerReachSql()](/code/functions/managerReachSql.md), [managerRow()](/code/functions/managerRow.md), [managersActive()](/code/functions/managersActive.md), [managersSettings()](/code/functions/managersSettings.md)

## Called from

- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerHasTeam()](/code/functions/managerHasTeam.md), [managerTeamPreviewIds()](/code/functions/managerTeamPreviewIds.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)


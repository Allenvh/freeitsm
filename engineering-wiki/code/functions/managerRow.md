# managerRow()

The manager's own row, or null if they cannot be a manager (missing, or left).

```php
function managerRow(PDO $conn, int $managerUserId): ?array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 113.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$managerUserId` | `int` |  | the manager's users.id (a portal user) |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 115 | `managerRow` | read [users](/code/tables/users.md) |  |

## Called from

- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerTeamUserIds()](/code/functions/managerTeamUserIds.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)


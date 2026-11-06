# managersSettings()

The settings, with their defaults.

```php
function managersSettings(PDO $conn): array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 62.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `managersSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [api/system/managers.php](/code/files/api/system/managers.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managersActive()](/code/functions/managersActive.md), [managerTeamUserIds()](/code/functions/managerTeamUserIds.md), [managerReachSql()](/code/functions/managerReachSql.md), [managerTeamPreviewIds()](/code/functions/managerTeamPreviewIds.md), [managerLinesEditable()](/code/functions/managerLinesEditable.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md), [managersNotifyNewTicket()](/code/functions/managersNotifyNewTicket.md)


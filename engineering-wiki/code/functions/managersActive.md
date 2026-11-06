# managersActive()

Can managers work at all on this install? Needs the switch on AND every table the rules depend on - fail closed otherwise.

```php
function managersActive(PDO $conn): bool
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 101 | `managersActive` | read [manager_grants](/code/tables/manager_grants.md) |  |

## Calls

[managersSettings()](/code/functions/managersSettings.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketViewsReady()](/code/functions/ticketViewsReady.md)

## Called from

- [api/system/managers.php](/code/files/api/system/managers.php.md) - in [managersStatus()](/code/functions/managersStatus.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerTeamUserIds()](/code/functions/managerTeamUserIds.md), [managersNotifyNewTicket()](/code/functions/managersNotifyNewTicket.md)


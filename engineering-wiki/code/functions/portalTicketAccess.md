# portalTicketAccess()

THE question every portal endpoint asks: may this portal user see this ticket, and as whom?

```php
function portalTicketAccess(PDO $conn, int $userId, int $ticketId): ?array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 324.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the signed-in portal user's users.id; 0 or less gives null |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array|null null = no (and the caller answers "not found", never "forbidden"). Otherwise: role      'requester' | 'manager' stub      true = a manager sees only that a confidential ticket exists can_reply / can_close ticket    the row (id, user_id, tenant_id, sensitivity)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 328 | `portalTicketAccess` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md), [managerCompany()](/code/functions/managerCompany.md), [managerRow()](/code/functions/managerRow.md), [managersSettings()](/code/functions/managersSettings.md), [managerTeamUserIds()](/code/functions/managerTeamUserIds.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md)
- [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md)
- [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md)
- [api/self-service/get_recording.php](/code/files/api/self-service/get_recording.php.md)
- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)
- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managersNotifyNewTicket()](/code/functions/managersNotifyNewTicket.md)


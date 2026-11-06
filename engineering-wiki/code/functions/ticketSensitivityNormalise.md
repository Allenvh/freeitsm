# ticketSensitivityNormalise()

Anything unrecognised reads as normal - the column's own default.

```php
function ticketSensitivityNormalise($value): string
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | a sensitivity as stored or submitted; anything not in TICKET_SENSITIVITIES gives 'normal' |

**Returns:** `string`

## Called from

- [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md)
- [api/tickets/assign_ticket.php](/code/files/api/tickets/assign_ticket.php.md)
- [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md)
- [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncEventFromTicket()](/code/functions/calendarSyncEventFromTicket.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [portalTicketAccess()](/code/functions/portalTicketAccess.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`, `TicketsService::updateTicket`
- [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) - in [ticketIsConfidential()](/code/functions/ticketIsConfidential.md)


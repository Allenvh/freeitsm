# managersNotifyNewTicket()

Tell the requester's managers that a new ticket has been raised, as System -> Managers says: 'none' (the default), 'email', or 'bell' - the self-service portal's own notification bell.

Called from `ticketDispatchCreated()` (includes/ticket_events.php), which every
route that announces a new ticket goes through, AFTER the confidential defaults
have been applied - so an HR-mailbox ticket is confidential before anybody is
told about it.

WHO: every manager who could open it, decided by `portalTicketAccess()` - the
same rule as the ticket page - and never somebody who would only see a
confidential stub. "Your team member raised a confidential ticket" is exactly
the thing confidentiality exists to stop.

Candidates are worked out BACKWARDS from the requester: everyone above them on
the reporting line, plus everyone with a management line. The rule then decides
for each, so a candidate list that is too generous costs a query, never a leak.

Never throws; a manager's notification is never worth the ticket.

```php
function managersNotifyNewTicket(PDO $conn, int $ticketId): void
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 385.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 393 | `managersNotifyNewTicket` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 405 | `managersNotifyNewTicket` | read [users](/code/tables/users.md) |  |
| 415 | `managersNotifyNewTicket` | read [manager_grants](/code/tables/manager_grants.md) |  |

## Calls

[managersActive()](/code/functions/managersActive.md), [managersEmailNewTicket()](/code/functions/managersEmailNewTicket.md), [managersSettings()](/code/functions/managersSettings.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)

## Called from

- [includes/ticket_events.php](/code/files/includes/ticket_events.php.md) - in [ticketDispatchCreated()](/code/functions/ticketDispatchCreated.md)


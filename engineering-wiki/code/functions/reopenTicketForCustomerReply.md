# reopenTicketForCustomerReply()

Reopen a ticket because its requester replied. No-op unless the setting is on AND the ticket is currently in a status flagged is_closed.

Reopens to the install's first OPEN status, preferring one literally called
"Open" and otherwise falling back to the lowest-ordered non-closed status — so
an install with a renamed workflow still lands somewhere sane rather than
having a status name hardcoded at it.

Deliberately defensive: a reply must never fail because reopening did. The
customer's message is the thing that matters; the status is a convenience.

```php
function reopenTicketForCustomerReply(PDO $conn, int $ticketId): bool
```

Defined in [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** bool true if the ticket was actually reopened (for the caller's UI message)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 77 | `reopenTicketForCustomerReply` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 92 | `reopenTicketForCustomerReply` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 104 | `reopenTicketForCustomerReply` | **update** [tickets](/code/tables/tickets.md) (`status_id`, `closed_datetime`, `updated_datetime`) |  |

## Calls

[customerReplyReopensTickets()](/code/functions/customerReplyReopensTickets.md)

## Called from

- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatIngestMessage()](/code/functions/webchatIngestMessage.md)

## Traps

> [!WARNING]
> **Line 100, in `reopenTicketForCustomerReply`:** leaving it set made a reopened ticket still count as closed everywhere that reads closed_datetime - reports, and the chat lookup, which would open a NEW ticket for the customer's next message rather than continue the one just reopened.


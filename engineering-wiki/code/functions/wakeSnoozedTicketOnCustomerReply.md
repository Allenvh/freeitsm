# wakeSnoozedTicketOnCustomerReply()

The customer came back while the ticket was asleep — wake it.

Sits alongside `reopenTicketForCustomerReply()` at all four inbound doors (email
import, portal reply, web chat, messaging ingest) for the same reason that one
is shared: "the customer replied" must mean the same thing whichever channel
they used. Snoozing is nearly always "waiting on them", so their reply is
exactly the event the snooze was waiting for — leaving it asleep would bury
the answer the desk asked for.

Defensive to a fault: a customer's message must never fail because waking did.

```php
function wakeSnoozedTicketOnCustomerReply(PDO $conn, int $ticketId): bool
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 273.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[wakeTicket()](/code/functions/wakeTicket.md)

## Called from

- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatIngestMessage()](/code/functions/webchatIngestMessage.md)


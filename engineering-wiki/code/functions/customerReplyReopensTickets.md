# customerReplyReopensTickets()

Is reopen-on-customer-reply switched on? Tickets → Settings → General.

Defaults to ON when the row is absent: the requester-facing email template has
always promised this behaviour, so honouring it is the answer that matches what
customers were already told. Reopening is non-destructive and visible (the
ticket comes back into the queue); the opposite failure — a customer's reply
silently landing on a closed ticket nobody looks at again — is not.

```php
function customerReplyReopensTickets(PDO $conn): bool
```

Defined in [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `customerReplyReopensTickets` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md) - in [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md)


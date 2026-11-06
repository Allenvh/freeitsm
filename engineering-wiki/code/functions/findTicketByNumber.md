# findTicketByNumber()

Find existing ticket by number.

FOLLOWS MERGES. Every notification FreeITSM has ever sent carries [SDREF:ABC-…] in
its subject, and those emails live in customers' inboxes forever. When ABC is later
merged into DEF, a reply to any of them still quotes ABC — and without this it would
append to a ticket that is closed, flagged as merged, and which nobody is watching.
The customer would get silence.

This is the single point every inbound reply passes through, which is exactly why
the redirect belongs here rather than at each call site.

```php
function findTicketByNumber($conn, $ticketNumber)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 865.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketNumber` |  |  | the ticket number taken from the subject tag; old and renumbered numbers are found too |

## Calls

[resolveMergedTicket()](/code/functions/resolveMergedTicket.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)


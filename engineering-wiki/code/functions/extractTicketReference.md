# extractTicketReference()

Extract ticket reference from subject

```php
function extractTicketReference($subject)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 839.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subject` |  |  | the inbound email's subject, which may carry an [SDREF:...] tag |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)


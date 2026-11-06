# getMailboxForTicket()

Get mailbox for a ticket based on associated emails

```php
function getMailboxForTicket($conn, $ticketId)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 209.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` |  |  | the ticket whose first email decides which mailbox replies go from |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 211 | `getMailboxForTicket` | read [emails](/code/tables/emails.md), read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Calls

[decryptMailboxRow()](/code/functions/decryptMailboxRow.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)


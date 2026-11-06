# getAttachmentById()

Get attachment by ID from database - only if it belongs to this ticket

```php
function getAttachmentById($id, $ticketId)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 543.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` |  |  | the email_attachments id from the image link |
| `$ticketId` |  |  | the ticket the attachment must belong to; anything else gives no row |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 546 | `getAttachmentById` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [processInlineImages()](/code/functions/processInlineImages.md)


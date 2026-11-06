# getAttachmentByCid()

Get attachment by CID and email_id from database - only if it belongs to this ticket

```php
function getAttachmentByCid($cid, $emailId, $ticketId)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 566.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cid` |  |  | the content id from the image link, possibly URL-encoded |
| `$emailId` |  |  | the emails.id from the image link |
| `$ticketId` |  |  | the ticket the attachment must belong to; anything else gives no row |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 572 | `getAttachmentByCid` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [processInlineImages()](/code/functions/processInlineImages.md)


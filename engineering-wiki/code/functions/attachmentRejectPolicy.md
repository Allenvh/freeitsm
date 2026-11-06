# attachmentRejectPolicy()

The install's policy for an attachment whose type we do not accept.

A setting rather than a decision made here, because both answers are defensible:
keeping the file loses nothing and an analyst can still look at it out of band,
while dropping it means the estate never stores content it has declared it does
not want. Defaults to keeping, because silently losing somebody's file is the
worse surprise.

```php
function attachmentRejectPolicy(PDO $conn): string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 923.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 928 | `attachmentRejectPolicy` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveAttachment()](/code/functions/saveAttachment.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [saveSentAttachments()](/code/functions/saveSentAttachments.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md)


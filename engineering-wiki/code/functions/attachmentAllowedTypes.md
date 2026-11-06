# attachmentAllowedTypes()

Which attachment types this install actually accepts.

A setting rather than a constant because the right answer is genuinely local: an
install that only ever receives email may not want to store video, while one running
the WhatsApp channel needs audio or the module does not work. Unset means the whole
catalogue, which is what makes a fresh install behave the way the modules expect.

⚠️ It can only ever NARROW. The stored value is intersected with
UPLOAD_TYPES_ATTACHMENT, so an unknown extension in the setting is ignored rather
than trusted — there is no way to talk this function into accepting .php, and an
administrator who empties the box gets the default back rather than an install that
refuses everything (which would look identical to the product being broken).

```php
function attachmentAllowedTypes(PDO $conn): array
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 953.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array ext => [mime, …], ready to pass as `$allowed`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 959 | `attachmentAllowedTypes` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/documents/save.php](/code/files/api/documents/save.php.md)
- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveAttachment()](/code/functions/saveAttachment.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [saveSentAttachments()](/code/functions/saveSentAttachments.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md)


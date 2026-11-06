# saveAttachment()

Save attachment to filesystem and database

```php
function saveAttachment($conn, $dbEmailId, $attachment, ?int $maxBytes = null)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 961.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$dbEmailId` |  |  | the emails.id the attachment belongs to; also names its folder on disk |
| `$attachment` |  |  | one Graph fileAttachment: name, contentType, contentId, isInline, contentBytes (base64) |
| `$maxBytes` | `?int` | `null` | the mailbox's per-attachment limit in bytes; null uses the install's |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1007 | `saveAttachment` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline`) |  |

## Calls

[attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)


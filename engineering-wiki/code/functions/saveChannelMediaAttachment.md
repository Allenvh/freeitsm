# saveChannelMediaAttachment()

Stores one downloaded media file as an attachment on the channel message's email row.

```php
function saveChannelMediaAttachment(
    PDO $conn,
    int $emailId,
    string $filename,
    string $contentType,
    string $data
): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 1043.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$emailId` | `int` |  | the emails row the message was stored as |
| `$filename` | `string` |  | the file name the sender gave - untrusted, for display only |
| `$contentType` | `string` |  | the content type the provider claimed (not stored - the bytes decide) |
| `$data` | `string` |  | the file's bytes |

**Returns:** array{stored:bool, reason:?string} — reason is set when the file was rejected or quarantined.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1065 | `saveChannelMediaAttachment` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline`) |  |

## Calls

[attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md)


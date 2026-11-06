# uploadStoreBytes()

Store ingested bytes safely, applying the install's policy for types we do not accept.

Unlike `uploadStoreFile()` this does NOT throw when a file is disallowed: inbound
email is not a user sitting in front of a form who can pick another file, and one
odd attachment must never cost somebody their ticket. It reports what it did and
lets the caller record it.

```php
function uploadStoreBytes(
    string $bytes,
    string $originalName,
    string $destDir,
    string $policy = ATTACHMENT_POLICY_STORE,
    array $allowed = UPLOAD_TYPES_ATTACHMENT,
    ?int $maxBytes = null
): array
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 820.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bytes` | `string` |  | the file's decoded contents |
| `$originalName` | `string` |  | the sender's filename - untrusted; cleaned, and only its extension is used |
| `$destDir` | `string` |  | absolute directory to store into; created (and protected) if absent |
| `$policy` | `string` | `ATTACHMENT_POLICY_STORE` | ATTACHMENT_POLICY_STORE \| ATTACHMENT_POLICY_DROP |
| `$allowed` | `array` | `UPLOAD_TYPES_ATTACHMENT` | ext => [mime, ...] accepted; defaults to the everyday attachment set |
| `$maxBytes` | `?int` | `null` | the size limit in bytes; null = the configured limit (attachmentMaxBytes()) |

**Returns:** array{stored:bool, quarantined:bool, stored_name:?string, path:?string, original_name:string, size:int, mime:string, reason:?string}

## Calls

[attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md), [uploadCleanName()](/code/functions/uploadCleanName.md), [uploadDetectMimeFromBytes()](/code/functions/uploadDetectMimeFromBytes.md), [uploadFormatBytes()](/code/functions/uploadFormatBytes.md), [uploadPrepareDir()](/code/functions/uploadPrepareDir.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveAttachment()](/code/functions/saveAttachment.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [saveSentAttachments()](/code/functions/saveSentAttachments.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md)
- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeAttachSnapshot()](/code/functions/mergeAttachSnapshot.md)


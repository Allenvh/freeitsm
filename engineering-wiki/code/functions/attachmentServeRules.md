# attachmentServeRules()

Decide how to serve a stored attachment, from its filename alone.

The stored content_type is deliberately NOT a parameter: there is no version of
this decision that should consult a value the sender chose.

```php
function attachmentServeRules(string $filename): array
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 738.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filename` | `string` |  | the attachment's original filename; only its extension decides the type |

**Returns:** array ['type' => string, 'inline' => bool, 'filename' => string] filename is header-safe (quotes and CR/LF removed).

## Called from

- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesDetectMime()](/code/functions/filesDetectMime.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomMessages()](/code/functions/warRoomMessages.md), [warRoomStoreAttachment()](/code/functions/warRoomStoreAttachment.md)


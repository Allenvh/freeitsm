# attachmentSendHeaders()

Emit the Content-Type / Content-Disposition / nosniff headers for an attachment. Callers still do their own authorisation and file-existence checks first.

```php
function attachmentSendHeaders(string $filename, int $size): void
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 758.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filename` | `string` |  | the attachment's original filename, for the type and the download name |
| `$size` | `int` |  | the file's size in bytes, sent as Content-Length |

**Returns:** `void`

## Calls

[attachmentServeRules()](/code/functions/attachmentServeRules.md)

## Called from

- [api/documents/download.php](/code/files/api/documents/download.php.md)
- [api/messaging/media.php](/code/files/api/messaging/media.php.md)
- [api/self-service/get_article_document.php](/code/files/api/self-service/get_article_document.php.md)
- [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md)
- [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md)
- [api/tickets/get_attachment.php](/code/files/api/tickets/get_attachment.php.md)
- [api/war-room/attachment.php](/code/files/api/war-room/attachment.php.md)


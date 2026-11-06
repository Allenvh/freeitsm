# uploadedFileParts()

The files the analyst attached, in the Graph fileAttachment shape that all three providers' builders take (Graph directly; SMTP and Gmail through includes/mime_message.php).

```php
function uploadedFileParts($attachments)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 379.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$attachments` |  |  | the files the analyst attached, from the request: [{name, type, content (base64)}, ...] |

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [buildEmailMessage()](/code/functions/buildEmailMessage.md)


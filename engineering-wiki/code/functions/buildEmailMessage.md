# buildEmailMessage()

Build email message structure for Graph API

```php
function buildEmailMessage($to, $cc, $subject, $body, $attachments, $ticketId)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 334.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` |  |  | the To addresses as typed, separated by ; or , |
| `$cc` |  |  | the Cc addresses as typed, separated by ; or ,; empty for none |
| `$subject` |  |  | the email subject |
| `$body` |  |  | the HTML body to send, thread included for a reply or forward |
| `$attachments` |  |  | the files the analyst attached, from the request: [{name, type, content (base64)}, ...] |
| `$ticketId` |  |  | the ticket being emailed from; only its own stored pictures are embedded |

## Calls

[parseRecipients()](/code/functions/parseRecipients.md), [processInlineImages()](/code/functions/processInlineImages.md), [uploadedFileParts()](/code/functions/uploadedFileParts.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)


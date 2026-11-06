# processInlineImages()

Process inline images in email body Converts image links the customer's mail client could never load into images carried inside the email itself (src="cid:...").

Two kinds of image are converted:

1. Links to a file stored on this ticket. An inbound email's pictures are
   saved with a link back into FreeITSM, and that link travels in the quoted
   thread of every reply and forward. check_mailbox_email.php writes it as
   "/api/tickets/get_attachment.php?cid=...&email_id=N" (older rows have
   "../api/tickets/..."). GH #158: this used to match only
   "api/get_attachment.php", a form nothing stores - it matched 0 rows, so
   thread pictures went out as broken links on EVERY provider.
   Only files belonging to THIS ticket are read, so a link typed into the
   editor's source view cannot mail out another ticket's (or company's) file.
   ⚠️ Capped at INLINE_THREAD_BUDGET bytes per email. Every reply re-sends the
   whole thread's pictures, and Graph refuses a sendMail request over 4 MB -
   uncapped, a picture-heavy thread would turn a send that used to work (with
   broken pictures) into one that fails. Past the cap the link stays as it was.

2. data: images - what the editor produces when a screenshot is pasted.
   Gmail and Outlook refuse to show a data: image in a received email.

```php
function processInlineImages($body, $ticketId)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 422.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` |  |  | the HTML body to send |
| `$ticketId` |  |  | the ticket being emailed from; links to another ticket's files are left alone |

## Calls

[getAttachmentByCid()](/code/functions/getAttachmentByCid.md), [getAttachmentById()](/code/functions/getAttachmentById.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [buildEmailMessage()](/code/functions/buildEmailMessage.md)


# includes/mime_message.php

> PHP - 178 lines

Build a raw RFC 2822 / MIME email — shared by the two send paths that hand the provider a finished message: SMTP (basic IMAP mailboxes) and the Gmail API. Microsoft Graph takes JSON instead and builds its own MIME.

`$parts` is a list of files in the SAME shape the Graph path already uses
(buildEmailMessage() in api/tickets/send_email.php), so one list feeds all
three providers:
  ['name' => 'report.pdf', 'contentType' => 'application/pdf',
   'contentBytes' => '<base64>', 'contentId' => 'x', 'isInline' => true]
A part with isInline + contentId is an image the HTML shows via src="cid:x";
anything else is an ordinary attachment.

Shape of the result, only as deep as it needs to be:
  no parts            -> text/html                       (what was always sent)
  inline images only  -> multipart/related { html, images }
  attachments         -> multipart/mixed { html | related, files }

GH #158: before this, SMTP and Gmail sent text/html only, so an analyst's
attachment appeared on the ticket and never reached the customer.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mimeEncodeHeader()](/code/functions/mimeEncodeHeader.md) | 29 | RFC 2047 encode a header value if it contains non-ASCII. |
| [mimeMessageId()](/code/functions/mimeMessageId.md) | 44 | A globally unique Message-ID on the sender's own domain, e.g. <3f9c...e1.1790805999@example.com>. Falls back to a fixed domain if the From address has none we can use. |
| [mimeBuildMessage()](/code/functions/mimeBuildMessage.md) | 59 | Builds a complete raw MIME message - headers, the HTML body, inline images and attachments - ready for SMTP or the Gmail API. |
| [mimeHtmlPart()](/code/functions/mimeHtmlPart.md) | 110 | The HTML body as a [headers, content] entity. |
| [mimeFilePart()](/code/functions/mimeFilePart.md) | 122 | One file (inline image or attachment) as a [headers, content] entity. |
| [mimeMultipart()](/code/functions/mimeMultipart.md) | 167 | Wrap [headers, content] entities in a multipart/<subtype> entity. |

## How it hangs together

**Included by:** [includes/gmail.php](/code/files/includes/gmail.php.md), [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md)


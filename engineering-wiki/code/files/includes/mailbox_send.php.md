# includes/mailbox_send.php

> PHP - 64 lines

Send one email FROM a ticket mailbox, through whatever that mailbox uses (Microsoft Graph, Gmail, or IMAP/SMTP), and record it in the send log.

Written for GH #170 (the oversized-email alert and auto-reply), and kept
separate from includes/template_email.php on purpose: a template email is about
a TICKET (merge codes, the SDREF subject, a copy filed on the ticket). This is a
plain message about the mailbox itself, which may have no ticket at all - a
rejected email never made one.

Never throws. Returns null on success, or the reason it could not send.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mailboxSendSimple()](/code/functions/mailboxSendSimple.md) | 29 | Sends one HTML email from a mailbox through its own provider (IMAP/SMTP, Gmail or Microsoft Graph) and logs it. |

## How it hangs together

**Includes:** [includes/email_log.php](/code/files/includes/email_log.php.md), [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), [includes/gmail.php](/code/files/includes/gmail.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Included by:** [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md)

**Calls:** [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [templateGraphContext()](/code/functions/templateGraphContext.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md)


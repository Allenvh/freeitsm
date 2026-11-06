# includes/self_service_email.php

> PHP - 180 lines

Self-service system emails (account verification, etc.).

The ticket email path (includes/template_email.php) is bound to a ticket +
a template. Account verification has neither, so this sends a plain system
email from the first active mailbox, reusing the same provider-branched send
logic (Gmail API / Microsoft Graph / IMAP-SMTP).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ssGetSendingMailbox()](/code/functions/ssGetSendingMailbox.md) | 15 | First active, send-capable mailbox (has credentials), decrypted. Null if none. |
| [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md) | 37 | Send a system email to one recipient. Returns true on success, false if no mailbox is configured or the send fails (caller decides what to do — the verification flow fails closed rather than pretend it sent). |
| [ssBuildVerifyUrl()](/code/functions/ssBuildVerifyUrl.md) | 104 | Absolute URL to the self-service email-verification page for a raw token. |
| [ssBuildResetUrl()](/code/functions/ssBuildResetUrl.md) | 123 | Absolute URL to the self-service password-reset page for a raw token. |
| [ssResetEmailBody()](/code/functions/ssResetEmailBody.md) | 144 | HTML body for the password-reset email. |
| [ssVerifyEmailBody()](/code/functions/ssVerifyEmailBody.md) | 166 | HTML body for the verification email. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 17 | `ssGetSendingMailbox` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/session_security.php](/code/files/includes/session_security.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/email_log.php](/code/files/includes/email_log.php.md), [includes/mailbox_imap.php](/code/files/includes/mailbox_imap.php.md), [includes/gmail.php](/code/files/includes/gmail.php.md)

**Included by:** [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md), [api/contracts/email_equipment_report.php](/code/files/api/contracts/email_equipment_report.php.md), [api/lms/tests.php](/code/files/api/lms/tests.php.md), [api/self-service/register.php](/code/files/api/self-service/register.php.md), [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md), [includes/managers.php](/code/files/includes/managers.php.md), [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Calls:** [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [mailboxCanSend()](/code/functions/mailboxCanSend.md), [requestScheme()](/code/functions/requestScheme.md), [templateGraphContext()](/code/functions/templateGraphContext.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md)


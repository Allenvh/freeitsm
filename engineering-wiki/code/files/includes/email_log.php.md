# includes/email_log.php

> PHP - 175 lines

Outbound email log.

The inbound half of this already existed (`mailbox_activity_log`, which records
every message that arrives and why it was imported or rejected). There was no
outbound equivalent: a successful send left a row in `emails`, and a FAILED send
left nothing anywhere except a line in the PHP error log on the server.

That gap is how issue #67 stayed invisible. A workflow send failure was at least
visible in the workflow run history, but the ticket acknowledgement template was
failing on exactly the same call, every time, and said so only here:

  [15-Aug-2026 13:30:51] Template email error (ticket_assigned, ticket 611):
  Graph API send failed: /me request is only valid with delegated authentication flow.

Nobody reads a file on the server until they already suspect a problem, and the
whole difficulty with a silent send failure is that nothing makes you suspect one.

⚠️ EVERY outbound path must log, success and failure alike. A log that covers some
routes is worse than none, because "no failures" then reads as "nothing failed"
when it may only mean "that route was never instrumented". The routes are listed
in EMAIL_LOG_ROUTES below; add to it when a new way to send email appears.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [emailLogRecord()](/code/functions/emailLogRecord.md) | 77 | Record one send attempt. NEVER throws and never returns a failure: logging a send must not be able to break the send it is logging, and must not turn a delivered email into an error the caller reports as undelivered. |
| [emailLogSent()](/code/functions/emailLogSent.md) | 126 | Convenience: a send that succeeded. |
| [emailLogFailed()](/code/functions/emailLogFailed.md) | 139 | Convenience: a send that failed, with whatever the provider said about it. |
| [emailLogRouteLabel()](/code/functions/emailLogRouteLabel.md) | 148 | Human label for a route key, falling back to the key itself for unknown ones. |
| [emailLogSkipped()](/code/functions/emailLogSkipped.md) | 172 | Convenience: an email that was deliberately NOT sent, and why. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 91 | `emailLogRecord` | **insert** [email_send_log](/code/tables/email_send_log.md) (`mailbox_id`, `ticket_id`, `route`, `provider`, `auth_mode`, `to_address`, `subject`, `status`, `error_message`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md)

**Included by:** [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md), [api/tickets/get_mailbox_outbound.php](/code/files/api/tickets/get_mailbox_outbound.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)


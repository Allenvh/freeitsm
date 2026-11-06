# api/auth/request_password_reset.php

> API endpoint - 174 lines

Request Password Reset

Accepts a username or email, generates a secure token, and sends a reset link
via the first configured mailbox (Microsoft Graph API).

POST JSON: { "identifier": "username_or_email" }

## The endpoint

| | |
|---|---|
| URL | `api/auth/request_password_reset.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php); Signed-in analyst (session check) (via includes/csrf.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [analysts](/code/tables/analysts.md) |  |
| 51 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 66 |  | **update** [password_reset_tokens](/code/tables/password_reset_tokens.md) (`used`) |  |
| 73 |  | **insert** [password_reset_tokens](/code/tables/password_reset_tokens.md) (`analyst_id`, `token_hash`, `expires_datetime`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/db.php](/code/files/includes/db.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/session_security.php](/code/files/includes/session_security.php.md), [includes/request_guard.php](/code/files/includes/request_guard.php.md), `api/includes/gmail.php`

**Calls:** [dbConnectionOptions()](/code/functions/dbConnectionOptions.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [mailboxCanSend()](/code/functions/mailboxCanSend.md), [requestScheme()](/code/functions/requestScheme.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md), [templateGraphContext()](/code/functions/templateGraphContext.md)


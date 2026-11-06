# api/change-management/send_share_email.php

> API endpoint - 362 lines

API Endpoint: Send share email for change record

## The endpoint

| | |
|---|---|
| URL | `api/change-management/send_share_email.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sendSmtpEmail()](/code/functions/sendSmtpEmail.md) | 128 | Send email via SMTP |
| [sendMailboxEmail()](/code/functions/sendMailboxEmail.md) | 182 | Send email via Microsoft 365 mailbox using Graph API |
| [getValidAccessToken()](/code/functions/getValidAccessToken.md) | 276 | Get valid access token (refresh if expired) |
| [refreshAccessToken()](/code/functions/refreshAccessToken.md) | 305 | Refresh the access token using mailbox configuration |
| [saveTokenData()](/code/functions/saveTokenData.md) | 353 | Save token data to database |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 105 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 188 | `sendMailboxEmail` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 357 | `saveTokenData` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/email_log.php](/code/files/includes/email_log.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [emailLogRecord()](/code/functions/emailLogRecord.md), [encryptValue()](/code/functions/encryptValue.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)


# api/knowledge/send_share_email.php

> API endpoint - 389 lines

API Endpoint: Send share email for knowledge article

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/send_share_email.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sendSmtpEmail()](/code/functions/sendSmtpEmail.md) | 144 | Send email via SMTP |
| [sendMailboxEmail()](/code/functions/sendMailboxEmail.md) | 209 | Send email via Microsoft 365 mailbox using Graph API |
| [getValidAccessToken()](/code/functions/getValidAccessToken.md) | 303 | Get valid access token (refresh if expired) |
| [refreshAccessToken()](/code/functions/refreshAccessToken.md) | 332 | Refresh the access token using mailbox configuration |
| [saveTokenData()](/code/functions/saveTokenData.md) | 380 | Save token data to database |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 121 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 215 | `sendMailboxEmail` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 384 | `saveTokenData` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/email_log.php](/code/files/includes/email_log.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [decryptValue()](/code/functions/decryptValue.md), [emailLogRecord()](/code/functions/emailLogRecord.md), [encryptValue()](/code/functions/encryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)


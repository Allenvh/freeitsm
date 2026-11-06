# api/tickets/get_reply_cleanup_settings.php

> API endpoint - 74 lines

API: Get Reply Cleanup AI settings Returns the configured model + tone, plus a masked preview of the API key so the analyst can tell whether one is set without leaking the key itself.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_reply_cleanup_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/reply_cleanup_prompt.php](/code/files/includes/reply_cleanup_prompt.php.md)

**Calls:** [buildReplyCleanupSystemPrompt()](/code/functions/buildReplyCleanupSystemPrompt.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [maskSecret()](/code/functions/maskSecret.md), [replyCleanupToneDescription()](/code/functions/replyCleanupToneDescription.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/tickets/test_reply_cleanup_key.php

> API endpoint - 90 lines

API: Test Reply Cleanup API key Sends a tiny request to Anthropic to validate the configured key + model. Returns success/failure with a friendly message.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/test_reply_cleanup_key.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)


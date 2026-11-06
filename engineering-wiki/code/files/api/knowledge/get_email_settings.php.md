# api/knowledge/get_email_settings.php

> API endpoint - 87 lines

API Endpoint: Get knowledge email settings

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/get_email_settings.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 54 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 64 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 74 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [maskSecret()](/code/functions/maskSecret.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/settings/get_system_settings.php

> API endpoint - 58 lines

API Endpoint: Get system settings

## The endpoint

| | |
|---|---|
| URL | `api/settings/get_system_settings.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [isMaskedSettingKey()](/code/functions/isMaskedSettingKey.md), [isSecretSettingName()](/code/functions/isSecretSettingName.md), [maskSecret()](/code/functions/maskSecret.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)


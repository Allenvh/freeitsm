# api/cmdb/get_ai_settings.php

> API endpoint - 54 lines

API: Get CMDB AI integration settings. Returns the masked API key, model, and custom instructions.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_ai_settings.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_AI (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [maskSecret()](/code/functions/maskSecret.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


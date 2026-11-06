# api/rfp-builder/get_ai_settings.php

> API endpoint - 60 lines

Return the RFP Builder AI settings (provider, model, masked api key). The api key is decrypted then masked before sending — plaintext never leaves the server.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_ai_settings.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_RFP_AI (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [isMaskedSettingKey()](/code/functions/isMaskedSettingKey.md), [maskSecret()](/code/functions/maskSecret.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


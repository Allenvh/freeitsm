# api/rfp-builder/save_ai_settings.php

> API endpoint - 75 lines

Save the RFP Builder AI settings (provider, model, api key). The api key is encrypted at rest. Submitting an empty or masked value (e.g. "****abcd") leaves the existing stored key unchanged.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/save_ai_settings.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_RFP_AI (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 63 |  | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`, `updated_datetime`) |  |
| 66 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


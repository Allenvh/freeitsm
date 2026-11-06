# api/forms/save_ai_settings.php

> API endpoint - 58 lines

API: Save the Forms AI settings.

Validates provider ∈ {anthropic, openai} and that a model is set.
The API key is encrypted at rest. An incoming `api_key` that's empty
or masked ("****abcd") means "leave the stored value alone" — same
pattern other modules use.

## The endpoint

| | |
|---|---|
| URL | `api/forms/save_ai_settings.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


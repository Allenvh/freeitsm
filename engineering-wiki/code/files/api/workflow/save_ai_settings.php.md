# api/workflow/save_ai_settings.php

> API endpoint - 64 lines

API: Save the workflow AI settings.

Validates provider ∈ {anthropic, openai} and that a model is set. The API
key is encrypted at rest (the `workflow_ai_api_key` entry in
ENCRYPTED_SETTING_KEYS triggers `encryptValue()` automatically on write).

Incoming `api_key` that's empty or starts with asterisks ("****abcd")
means "leave the stored value alone" — same masked-no-change pattern
the other settings pages use.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/save_ai_settings.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


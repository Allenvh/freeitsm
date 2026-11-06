# api/knowledge/save_email_settings.php

> API endpoint - 135 lines

API Endpoint: Save knowledge email settings

Despite the name this writes THREE tabs' worth of settings — the Email tab's SMTP
credentials, the Recycle bin's retention, and (by a legacy path) the AI and OpenAI API
keys. Each is a separate permission, so a single guard at the top of the file could
never be right: it would have to cover three audiences at once.

So authorisation is PER KEY, against the keys this request will actually write, using
the same helper and ownership map as every other shared settings writer (#829). The map
derives from knowledge/settings/manifest.php.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/save_email_settings.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 116 |  | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`, `updated_datetime`) |  |
| 122 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


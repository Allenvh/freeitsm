# api/tasks/save_settings.php

> API endpoint - 128 lines

API: Tasks — Save module settings Upserts tasks_* keys into system_settings.

This endpoint serves THREE different settings tabs — Calendar, Card and Tags — each of
which is a separate permission. So a single guard at the top of the file would be
meaningless, exactly as it was for the shared settings writer (#829): it would have to
cover three audiences at once.

So authorisation is PER KEY, reusing the same helper and the same ownership map as
api/settings/save_system_settings.php. The map derives from tasks/settings/manifest.php,
so the tab that shows a setting and the permission that guards it are one declaration.

## The endpoint

| | |
|---|---|
| URL | `api/tasks/save_settings.php` |
| Protected by | Module access: tasks (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 114 |  | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`) |  |
| 118 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md)

**Calls:** [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [settingKeyOwner()](/code/functions/settingKeyOwner.md)


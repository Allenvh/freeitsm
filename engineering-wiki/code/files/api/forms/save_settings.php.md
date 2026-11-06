# api/forms/save_settings.php

> API endpoint - 73 lines

API Endpoint: Save forms module settings

## The endpoint

| | |
|---|---|
| URL | `api/forms/save_settings.php` |
| Protected by | Module access: forms (requireModuleAccessJson); Permission: ? (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 |  | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`, `updated_datetime`) |  |
| 62 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


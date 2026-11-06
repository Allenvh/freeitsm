# api/system/ai/save_settings.php

> API endpoint - 55 lines

API: Save the AI config for a namespace. POST JSON { ns, provider, model, api_key? } A masked/empty api_key leaves the stored key untouched.

## The endpoint

| | |
|---|---|
| URL | `api/system/ai/save_settings.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/settings_keys.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Calls:** [aiSettingsIsValidNs()](/code/functions/aiSettingsIsValidNs.md), [aiSettingsSave()](/code/functions/aiSettingsSave.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAiNamespaceJson()](/code/functions/requireAiNamespaceJson.md)


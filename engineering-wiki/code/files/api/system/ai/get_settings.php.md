# api/system/ai/get_settings.php

> API endpoint - 41 lines

API: Get the UI-safe AI config for a namespace. GET ?ns=<namespace> Returns: { success, provider, model, has_key, masked_key } Never returns the plaintext API key.

## The endpoint

| | |
|---|---|
| URL | `api/system/ai/get_settings.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/settings_keys.php) |
| Reads | `GET ns` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Calls:** [aiSettingsForUi()](/code/functions/aiSettingsForUi.md), [aiSettingsIsValidNs()](/code/functions/aiSettingsIsValidNs.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAiNamespaceJson()](/code/functions/requireAiNamespaceJson.md)


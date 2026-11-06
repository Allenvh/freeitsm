# api/system/ai/test_connection.php

> API endpoint - 69 lines

API: Test the saved AI config for a namespace with a tiny live call. POST JSON { ns } Returns: { success, provider, model, duration_ms, reply } or { success:false, error }

## The endpoint

| | |
|---|---|
| URL | `api/system/ai/test_connection.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/settings_keys.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Calls:** [aiExplainFailure()](/code/functions/aiExplainFailure.md), [aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsIsValidNs()](/code/functions/aiSettingsIsValidNs.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAiNamespaceJson()](/code/functions/requireAiNamespaceJson.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

